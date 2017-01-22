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
	private $tool;

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
	 * Init
	 * @param  array 	$dataSet 	Values to test
	 * @param  array  	$rules 		Array of validation rules
	 */
	public function __construct($dataSet, $rules)
	{
		$this->validator = new ArrayWrapper();
		$this->dataSet = new ArrayWrapper($dataSet);
		$this->rules = $rules;
	}

	/**
	 * Launch validation
	 * @return boolean
	 */
	public function validate()
	{
		foreach ($this->rules as $rule) {
			list($fields, $type, $options) = $this->parseRule($rule);

			if($field && $type) {
				if(!$validator = $this->tool->$type) {
					$validator = $this->create();
					$this->tool->$type = $validator;
				}

				$validator->check($this->dataSet, $fields, $options);
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
	 * [parseRule description]
	 * @param  [type] $rule [description]
	 * @return [type]       [description]
	 */
	protected function parseRule($rule)
	{
		if(!is_array($rule) || (count($rule) < 2)) {
			throw new ValidatorException('Invalid declaration of validation rule');
		}

		is_array($rule[0]) || $rule[0] = [$rule[0]];

		if(!is_string($rule[1])) {
			throw new ValidatorException('Validator name: Expected string');
		}

		if(isset($rule[2])) {
			is_array($rule[2]) || $rule[2] = [$rule[2]];
		} else {
			$rule[2] = [];
		}

		return $rule;
	}
}