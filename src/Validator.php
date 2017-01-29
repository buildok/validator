<?php
namespace buildok\validator;

use buildok\validator\exceptions\ValidatorException;
use buildok\helpers\ArrayWrapper;

/**
 *
 */
class Validator
{
	/**
	 * Validators
	 * @var ArrayWrapper;
	 */
	private $validators;

	/**
	 * Object to test
	 * @var ArrayWrapper
	 */
	private $dataSet;

	/**
	 * Array of validation rules
	 * @var array
	 */
	private $rules;

	/**
	 * Validation errors
	 * @var ArrayWrapper
	 */
	private $errors;

	/**
	 * Init
	 *
	 * @example
	 * 		$rules = [
	 *           ['field_0', 'validator'],
	 *           [['field_1', 'field_2'], 'validator'],
	 *           [['field_3', 'field_4'], 'validator', ['option_name' => 'some_value']],
	 *      ];
	 * @param  array $dataSet Values to test
	 * @param  array $rules Array of validation rules
	 */
	public function __construct($dataSet, $rules)
	{
		if (!is_array($rules)) {
			throw new ValidatorException('Invalid declaration of validation rule');
		}

		$this->validators = new ArrayWrapper;
		$this->dataSet = new ArrayWrapper($dataSet);
		$this->rules = $rules;
		$this->errors = new ArrayWrapper;
	}

	/**
	 * Launch validation
	 * @return boolean
	 */
	public function validate()
	{
		$this->errors->set();

		foreach ($this->rules as $key => $rule) {
			list($fields, $type, $options) = $this->parseRule($rule);

			if (!$validator = $this->validators->$type) {
				$validator = $this->create($type);
				$this->validators->$type = $validator;
			}

			foreach ($fields as $key => $field) {
				$value = $this->dataSet->$field;

				if (!$validator->validate($value, $options)) {
					$this->addErrors($field, $validator->getErrors());
				}
			}
		}

		return !$this->hasErrors();
	}

	/**
	 * Returns TRUE if it has errors else FALSE
	 * @return boolean
	 */
	public function hasErrors()
	{
		return (bool)$this->errors->getData();
	}

	/**
	 * Returns validation errors
	 * @param  string $field Field name
	 * @return array
	 */
	public function getErrors($field = null)
	{
		return (($field) ? $this->errors->$field : $this->errors->getData());
	}

	/**
	 * Add errors
	 * @param string $field Field name
	 * @param array $errors Validation errors
	 */
	private function addErrors($field, $errors)
	{
		$this->errors->$field = array_merge((array)$this->errors->$field, $errors);
	}

	/**
	 * Create validator
	 * @param  string 	$type  		Type of validator
	 * @return BaseValidator		Object of validator
	 *
	 * @throws ValidatorException
	 */
	private function create($type)
	{
        $class = 'buildok\\validator\\types\\' . ucfirst($type). 'Validator';
        if (!class_exists($class)) {
            throw new ValidatorException('Unknown validator: ' . $type);
        }

        $validator = new $class;

        return $validator;
	}

	/**
	 * Check rules format
	 * @param  array $rule Array of validation rules
	 * @return array
	 *
	 * @throws ValidatorException
	 */
	private function parseRule($rule)
	{
		if (!is_array($rule) || (count($rule) < 2)) {
			throw new ValidatorException('Invalid declaration of validation rule');
		}

		is_array($rule[0]) || $rule[0] = [$rule[0]];

		if (!is_string($rule[1])) {
			throw new ValidatorException('Validator name: Expected string');
		}

		if (isset($rule[2])) {
			if(!is_array($rule[2])) {
				throw new ValidatorException('Validator options: Expected array');
			}
		} else {
			$rule[2] = [];
		}
		$rule[2] = new ArrayWrapper($rule[2]);

		return $rule;
	}
}