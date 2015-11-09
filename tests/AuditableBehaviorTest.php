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
        $this->assertEquals($testObject->getOriginalFieldValues(false), array(101, 'reynaldocastellano@gmail.com'));
    }
    
    public function testPreservationOfOriginalValuesAfterSave()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $testObject->hydrate($origValues);
        $this->assertEquals($testObject->getOriginalFieldValues(false), $origValues);
        $testObject->setEmailAddress('logancastellano@gmail.com');
        $this->assertEquals($testObject->getEmailAddress(), 'logancastellano@gmail.com');
        $testObject->save();
        $this->assertEquals($testObject->getOriginalFieldValues(false), $origValues);
    }
    
    public function testPreservationOfOriginalValuesAfterDelete()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $testObject->hydrate($origValues);
        $testObject->delete();
        $this->assertEquals($testObject->getOriginalFieldValues(false), $origValues);
    }
    
    public function testMappedOriginalValues()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $testObject->hydrate($origValues);
        $this->assertEquals($testObject->getOriginalFieldValues(true), array('id' => 101, 'email_address' => 'reynaldocastellano@gmail.com'));
    }
    
    public function testWasNewPropertyDeclaration()
    {
    	$testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        $this->assertTrue($refTestObject->hasProperty('wasNew'), 'The property "was_new" was NOT declared in model');
    }

    public function testWasModifiedPropertyDeclaration()
    {
    	$testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        $this->assertTrue($refTestObject->hasProperty('wasModified'), 'The property "was_modified" was NOT declared in model');
    }

    public function testWasNewMethodDeclaration()
    {
        $testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        $this->assertTrue($refTestObject->hasMethod('wasNew'));
    }

    public function testWasModifiedMethodDeclaration()
    {
        $testObject = new TestModel();
        $refTestObject = new ReflectionObject($testObject);
        $this->assertTrue($refTestObject->hasMethod('wasModified'));
    }

    public function testPreservationOfWasNewAfterSave()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        // $testObject->setId(101);
        // $testObject->setEmailAddress('logancastellano@gmail.com');
        // $this->assertEquals(false, $testObject->wasNew());
        // $testObject->save();
        // $this->assertEquals(true, $testObject->wasNew());
        // $testObject->setEmailAddress('reynaldocastellano@gmail.com');
        // $this->assertEquals(true, $testObject->wasNew());
        // $testObject->save();
        $this->assertEquals(false, $testObject->wasNew());
        $testObject->hydrate($origValues);
        $this->assertEquals(false, $testObject->wasNew());
        $testObject->setEmailAddress('logancastellano@gmail.com');
        $testObject->save();
        $this->assertEquals(false, $testObject->wasNew());
        $testObject->delete();
        $this->assertEquals(false, $testObject->wasNew());
    }

    public function testPreservationOfWasModified()
    {
        $origValues = array(101, 'reynaldocastellano@gmail.com');
        $testObject = new TestModel();
        $this->assertEquals(false, $testObject->wasModified());
        $testObject->hydrate($origValues);
        $testObject->setEmailAddress('logancastellano@gmail.com');
        $this->assertEquals(false, $testObject->wasModified());
        $testObject->save();
        $this->assertEquals(true, $testObject->wasModified());
        $testObject->delete();
        $this->assertEquals(true, $testObject->wasModified());
    }
    
}