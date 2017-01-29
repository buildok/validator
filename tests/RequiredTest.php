<?php
namespace buildok\validator\test;

use PHPUnit\Framework\TestCase;
use buildok\validator\Validator;

/**
 * RequiredTest class
 *
 * Test case for [required] validator
 * @see buildok\validator\RequiredValidator
 */
class RequiredTest extends TestCase
{
    private $dataSet;

    public function setUp()
    {
        $this->dataSet = [
            'field1' => 1,
            'field2' => null,
        ];
    }

    /**
     * Correct data set
     */
    public function testValidationTrue()
    {
        $rules = [
            ['field1', 'required']
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertTrue($validator->validate());

        return $validator;
    }

    /**
     * @depends testValidationTrue
     */
    public function testNoErrors($validator)
    {
        $this->assertFalse($validator->hasErrors());
    }

    /**
     * Wrong data set
     */
    public function testValidationFalse()
    {
        $rules = [
            ['field3', 'required']
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertFalse($validator->validate());

        return $validator;
    }

    /**
     * @depends testValidationFalse
     */
    public function testHasErrors($validator)
    {
        $this->assertTrue($validator->hasErrors());
    }

    /**
     * NULL is equal to not set
     */
    public function testNullValue()
    {
        $rules = [
            ['field2', 'required']
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertFalse($validator->validate());

        return $validator;
    }

    /**
     * @depends testNullValue
     */
    public function testErrorsArray($validator)
    {
        $errors = $validator->getErrors();
        $this->assertTrue(is_array($errors));

        return $errors;
    }

    /**
     * @depends testErrorsArray
     */
    public function testErrorField($errors)
    {
        $this->assertArrayHasKey('field2', $errors);
    }

    /**
     * @depends testNullValue
     */
    public function testFieldErrors($validator)
    {
        $errors = $validator->getErrors('field2');
        $this->assertTrue(is_array($errors));

        return $errors;
    }

    /**
     * @depends testFieldErrors
     */
    public function testErrorMessage($errors)
    {
        $this->assertEquals('is required', $errors[0]);
    }

}