<?php
namespace buildok\validator;

use buildok\exceptions\ValidatorException;
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
	 * @param  array 	$dataSet 	Values to test
	 * @param  array  	$rules 		Array of validation rules
	 */
	public function __construct($dataSet, $rules)
	{
		$this->validators = new ArrayWrapper();
		$this->dataSet = $dataSet;
		if(!is_array($rules)) {
			throw new ValidatorException('Invalid declaration of validation rule');
		}
		$this->rules = $rules;
		$this->errors = new ArrayWrapper();
	}

	/**
	 * Launch validation
	 * @return boolean
	 */
	public function validate()
	{
		$this->errors->set();

		foreach ($this->rules as $rule) {
			list($fields, $type, $options) = $this->parseRule($rule);

			if($fields && $type) {
				if(!$item = $this->validators->$type) {

					$item = $this->create($type);
					$this->validators->$type = $item;
				}

				if(!$item->validate($this->dataSet, $fields, $options)) {
					foreach ($item->getErrors as $key => $field) {
						$this->errors->$field[] = $field;
					}
				}
			}
		}
	}

	/**
	 * Create validator
	 * @param  string 	$type  		Type of validator
	 * @return BaseValidator		Object of validator
	 *
	 * @throws ValidatorException
	 */
	protected function create($type)
	{
        $class = 'buildok\\validator\\types\\' . ucfirst($type);
        if(!class_exists($class)) {
            throw new ValidatorException('Unknown validator: ' . $type);
        }

        $validator = new $class;

        return $validator;
	}

	/**
	 * Parse validation rule
	 *
	 * Returns formated validation rule like as [
	 * 		array 	-- Array of fields to validate
	 * 		string 	-- Validator name
	 * 		array 	-- Additional options for validator
	 * ]
	 * @param  array $rule Validation rule
	 * @return array
	 *
	 * @throws ValidatorException
	 */
	protected function parseRule($rule)
	{
		if(!is_array($rule) || (count($rule) < 2)) {
			throw new ValidatorException('Invalid declaration of validation rule');
		}

		is_array($rule[0]) || $rule[0] = [$rule[0]];

		if(!is_string($rule[1])) {
			throw new ValidatorException('Validator name: expected string');
		}

		if(isset($rule[2])) {
			is_array($rule[2]) || $rule[2] = [$rule[2]];
		} else {
			$rule[2] = [];
		}

		return $rule;
	}
}