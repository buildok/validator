<?php
namespace buildok\validator\test;

use PHPUnit\Framework\TestCase;
use buildok\validator\Validator;

/**
 * RequiredTest class
 *
 * Test case for [string] validator
 * @see buildok\validator\StringValidator
 */
class StringTest extends TestCase
{
    private $dataSet;

    public function setUp()
    {
        $this->dataSet = [
            'field1' => 1,
            'field2' => 'very long string',
            'field3' => 'very short string'
        ];
    }

    /**
     * Correct data set
     */
    public function testValidationTrue()
    {
        $rules = [
            ['field2', 'string']
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertTrue($validator->validate());
    }

    /**
     * Wrong data set
     */
    public function testValidationFalse()
    {
        $rules = [
            ['field1', 'string']
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertFalse($validator->validate());

        return $validator->getErrors();
    }

    /**
     * @depends testValidationFalse
     */
    public function testErrorMsg($errors)
    {
        $this->assertEquals('is should be string', $errors['field1'][0]);
    }

    public function testOptionsFalse()
    {
        $rules = [
            ['field2', 'string', ['max' => 1]],
            ['field3', 'string', ['min' => 100]]
        ];

        $validator = new Validator($this->dataSet, $rules);
        $this->assertFalse($validator->validate());

        return $validator->getErrors();
    }

    /**
     * @depends testOptionsFalse
     */
    public function testMaxErrorMsg($errors)
    {
        $this->assertEquals('is too long string (max = 1)', $errors['field2'][0]);
    }

    /**
     * @depends testOptionsFalse
     */
    public function testMinErrorMsg($errors)
    {
        $this->assertEquals('is too short string (min = 100)', $errors['field3'][0]);
    }
}