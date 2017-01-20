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

	public function __construct($value, ArrayWrapper $rules)
	{
		$this->value = $value;
		$this->rules = $rules;
	}

	/**
	 * [validate description]
	 * @return boolean
	 */
	abstract public function validate();
}