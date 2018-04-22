# UnitTest-JSON-Schema
A simple and easy to use class to aid in the validation and testing of a JSON-schema.

## How to Install
```
composer require "barkermn01/unittest-josn-schema"
```

## How to use
#### Sample draft-7 schema for validation
```json+schema
{
  "$schema":"http://json-schema.org/draft-07/schema",
  "properties":{
    "test":{
      "type":"string"
    }
  }
}
```
#### Supply a schema in test cases manually.
```php
$SchemaName = "test.schema.json";
$schemaToTest = file_get_contents(__DIR__ . "/test.schema.json");
				
$tester = new SchemaTester;
$tester->DefineSchema("http://json-schema.org/draft-07/schema");
$tester->TestSchema($schemaToTest);
$this->assertFalse($tester->hasErrors(), "Schema '{$SchemaName}' failed vailidation: '".$tester->getErrors());
```

#### Allow a schema to define it's own schema to be validated against
```php
$SchemaName = "test.schema.json";
$schemaToTest = file_get_contents(__DIR__ . "/test.schema.json");
				
$tester = new SchemaTester;
$tester->TestSchema($schemaToTest);
$this->assertFalse($tester->hasErrors(), "Schema '{$SchemaName}' failed vailidation: '".$tester->getErrors());
```

# License
Licensed under the Appache-2 license.
