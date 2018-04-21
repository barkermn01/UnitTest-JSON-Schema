<?php
namespace UnitTestJSONSchema;

use Seld\JsonLint\JsonParser;


class SchemaTester{
	// holds the schema we're testing against
	private $TestSchemaLocation = NULL;
	
	// holds any errors that result from testing
	private $errors = [];
	
	// have we finished testing
	private $testComplete = false;
	
	// allows for a user to defined the schema to test against
	public function DefineSchema(string $uri)
	{
		$this->TestSchemaLocation = $uri;
	}
	
	public function TestSchema(string $json)
	{
		// test json lint if passes $obj will be set
		try{
			$parser = new JsonParser();
			$parser->lint($json, JsonParser::DETECT_KEY_CONFLICTS);
		}catch(\Exception $e){
			// failed record the error
			$this->errors[] = "JSON Lint Failure: ".$e->getMessage();
			$this->testComplete = true;
		}
		
		$obj = $parser->parse($json);
		
		// if we failed lint stop testing
		if($this->testComplete){
			return;
		}
		
		// holds the schema path we need to download
		$schemaUri = NULL;
		// do we have a schema pre defined
		if(is_null($this->TestSchemaLocation)) {
			// hack past php's use of $
			$schemaFeild = "\$schema";
			
			// does the schema define a schema to test against
			if(isset($obj->$schemaFeild)){
				$schemaUri = $obj->$schemaFeild;
			}else{
				throw new \Exception("No JSON-Schema schema to test against not supplied by schema or test implementation");
			}
		}else{
			$schemaUri = $this->TestSchemaLocation;
		}
		
		$validator = new \JsonSchema\Validator;
		$validator->validate($obj, (object)['$ref' => $schemaUri]);
		
		if (!$validator->isValid()) {
			echo "JSON does not validate. Violations:\n";
			foreach ($validator->getErrors() as $error) {
				$this->errors[] = sprintf("[%s] %s\n", $error['property'], $error['message']);
			}
		}
		$testComplete = true;
		return;
	}
	
	public function hasErrors()
	{
		return count($this->errors) > 0;
	}
	
	public function getErrors()
	{
		if(empty($this->errors)){
			return false;
		}
		return implode("\r\n", $this->errors);
	}
}