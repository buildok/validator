<?php
namespace buildok\validator\test;

use PHPUnit\Framework\TestCase;
use buildok\validator\Validator;
use buildok\validator\exceptions\ValidatorException;

/**
 * ValidatorTest class
 *
 * Test case for Validator class
 * @see buildok\validator\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @expectedException     buildok\validator\exceptions\ValidatorException
     * @expectedExceptionMessage Invalid declaration of validation rule
     */
    public function testInvalidRules()
    {
        $dataSet = [];
        $rules = '';

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
    }

    /**
     * @expectedException     buildok\validator\exceptions\ValidatorException
     * @expectedExceptionMessage Invalid declaration of validation rule
     */
    public function testFieldsNotSet()
    {
        $dataSet = [];
        $rules = [
            ['required']
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
    }

    /**
     * @expectedException     buildok\validator\exceptions\ValidatorException
     * @expectedExceptionMessage Validator name: Expected string
     */
    public function testInvalidValdatorName()
    {
        $dataSet = [];
        $rules = [
            ['field', ['required']]
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
    }

    /**
     * @expectedException     buildok\validator\exceptions\ValidatorException
     * @expectedExceptionMessage Validator options: Expected array
     */
    public function testInvalidOptions()
    {
        $dataSet = [];
        $rules = [
            ['field', 'validator', 'options']
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
    }

    /**
     * @expectedException     buildok\validator\exceptions\ValidatorException
     * @expectedExceptionMessage Unknown validator: validator
     */
    public function testUnknownValdator()
    {
        $dataSet = [];
        $rules = [
            ['field', 'validator']
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
    }

    public function testCustomMessage()
    {
        $dataSet = [
            'field1' => 1,
            'field2' => null,
        ];
        $rules = [
            ['field2', 'required', ['message' => 'custom error message']]
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();
        $errors = $validator->getErrors('field2');

        $this->assertEquals('custom error message', $errors[0]);
    }

    public function testSomeRules()
    {
        $dataSet = [
            'field1' => 1,
            'field2' => null,
        ];
        $rules = [
            [['field1', 'field3'], 'required'],
            ['field2', 'required', ['message' => 'custom error message']],
        ];

        $validator = new Validator($dataSet, $rules);
        $validator->validate();

        $this->assertTrue($validator->hasErrors());

        return $validator->getErrors();
    }

    /**
     * @depends testSomeRules
     */
    public function testCountErrorFields($errors)
    {
        $this->assertCount(2, $errors);
    }

    /**
     * @depends testSomeRules
     */
    public function testField2($errors)
    {
        $this->assertArrayHasKey('field2', $errors);
    }

    /**
     * @depends testSomeRules
     */
    public function testField3($errors)
    {
        $this->assertArrayHasKey('field3', $errors);
    }

    /**
     * @depends testSomeRules
     */
    public function testField2Msg($errors)
    {
        $this->assertEquals('custom error message', $errors['field2'][0]);
    }

    /**
     * @depends testSomeRules
     */
    public function testField3Msg($errors)
    {
        $this->assertEquals('is required', $errors['field3'][0]);
    }
}