<?php

use PHPUnit\Framework\TestCase;
use UnitTestJSONSchema\SchemaTester;

class JsonParserTest extends TestCase
{
	public function testSchemaDefinedValidation()
	{
		$schemaToTest = file_get_contents(__DIR__ . "/test.schema.json");
				
		$tester = new SchemaTester;
		
		$tester->DefineSchema("http://json-schema.org/draft-07/schema");
		$tester->TestSchema($schemaToTest);
		
		$this->assertFalse($tester->hasErrors(), $tester->getErrors());
	}
	
	public function testInSchemaDefinitionValidation()
	{
		$schemaToTest = file_get_contents(__DIR__ . "/test.schema.json");
			 
		$tester = new SchemaTester;
		$tester->TestSchema($schemaToTest);
		
		$this->assertFalse($tester->hasErrors(), $tester->getErrors());
	}
}