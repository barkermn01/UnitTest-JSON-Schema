<?php
namespace barkermn01\UnitTestJSONSchema;

use Seld\JsonLint\JsonParser;

class UnitTestJSONSchema{
	// holds the schema we're testing against
	private $definedSchema = NULL;
	
	// holds any errors that result from testing
	private $errors = [];
	
	// have we finished testing
	private $testComplete = false;
	
	// allows for a user to defined the schema to test against
	public function defineSchema(string $uri)
	{
		$this->definedSchema = $uri;
	}
	
	public function TestSchema(string $schema)
	{
		$obj = null;
		// test json lint if passes $obj will be set
		try{
			$parser = new JsonParser();
			$obj = $parser->lint($json);
		}catch(\Exception $e){
			// failed record the error
			$this->errors[] = "JSON Lint Failure: ".$e->getMessage();
			$this->testComplete = true;
		}
		
		// if we failed lint stop testing
		if($this->testComplete){
			return;
		}
		
		// holds the schema path we need to download
		$schemaUri = NULL;
		
		// do we have a schema pre defined
		if(is_null($this->definedSchema)) {
			// hack past php's use of $
			$schemaFeild = "\$schema";
			
			// does the schema define a schema to test against
			if(isset($obj->$schemaFeild)){
				$schemaUri = $obj->$schemaFeild;
			}else{
				throw new Exception("No JSON-Schema schema to test against not supplied by class or caller");
			}
		}else{
			$schemaUri = $this->definedSchema;
		}
		
		$validator = new JsonSchema\Validator;
		$validator->validate($data, (object)['$ref' => $schemaUri]);
		
		if (!$validator->isValid()) {
			echo "JSON does not validate. Violations:\n";
			foreach ($validator->getErrors() as $error) {
				$this->errors[] = sprintf("[%s] %s\n", $error['property'], $error['message']);
			}
		}
		$testComplete = true;
		return;
	}
	
	public function getErrors()
	{
		if(empty($this->errors)){
			return false;
		}
		return $this->errors;
	}
}