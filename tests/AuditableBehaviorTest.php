<?php

class MyAwesomeBehaviorTest extends \PHPUnit_Framework_TestCase
{

    private $con;

    public function setUp()
    {
        if (!class_exists('TestModel')) {
            $schema = <<<EOF
<database name="TestDatabase" defaultIdMethod="native">
    <table name="TestModel">
        <column name="id" phpName="Id" type="SMALLINT" primaryKey="true" autoIncrement="true" required="true" />
        <column name="email_address" phpName="EmailAddress" type="VARCHAR" size="100" required="true" />
        <behavior name="auditable" />
    </table>
</database>
EOF;
            $builder = new PropelQuickBuilder();
            $config  = $builder->getConfig();
            $config->setBuildProperty('behavior.auditable.class', __DIR__.'/../src/AuditableBehavior');
            $builder->setConfig($config);
            $builder->setSchema($schema);
            $this->con = $builder->build();
        }
    }
    
    public function testPropertyDeclaration()
    {
        $testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        
        $this->assertTrue($refTestObject->hasProperty('originalFieldValues'), 'The property "originalFieldValues" was NOT declared in model');
    }
    
    public function testMethodDeclaration()
    {
        $testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        $this->assertTrue($refTestObject->hasMethod('getOriginalFieldValues'));
    }
    
    public function testInitialisationOfOriginalValues()
    {
        $testObject = new TestModel();
        $testObject->hydrate(array(101, 'reynaldocastellano@gmail.com'));
        $this->assertEquals($testObject->getOriginalFieldValues(), array(101, 'reynaldocastellano@gmail.com'));
    }
    
    public function testPreservationOfOriginalValuesAfterSave()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $testObject->hydrate($origValues);
        $this->assertEquals($testObject->getOriginalFieldValues(), $origValues);
        $testObject->setEmailAddress('logancastellano@gmail.com');
        $this->assertEquals($testObject->getEmailAddress(), 'logancastellano@gmail.com');
        $testObject->save();
        $this->assertEquals($testObject->getOriginalFieldValues(), $origValues);
    }
    
    public function testPreservationOfOriginalValuesAfterDelete()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $testObject->hydrate($origValues);
        $testObject->delete();
        $this->assertEquals($testObject->getOriginalFieldValues(), $origValues);
    }
}