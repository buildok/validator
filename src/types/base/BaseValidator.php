<?php
namespace buildok\validator\types\base;

use buildok\helpers\ArrayWrapper;

/**
*
*/
abstract class BaseValidator
{
	/**
	 * Object of validation rules
	 * @var ArrayWrapper
	 */
	protected $rules;

	/**
	 * Value to test
	 * @var mixed
	 */
	protected $value;

	/**
	 * Validation errors
	 * @var array
	 */
	private $errors;

	public function __construct($rules, $value = null)
	{
		$this->value = $value;
		$this->rules = new ArrayWrapper($rules);
		$this->errors = [];
	}

	/**
	 * Returns validation errors
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Returns TRUE if it has errors else FALSE
	 * @return boolean
	 */
	public function hasErrors()
	{
		return (bool)$this->errors;
	}

	/**
	 * Main validation function
	 * @return boolean
	 */
	abstract public function validate();
}