<?php
namespace buildok\validator;

use buildok\helpers\ArrayWrapper;
use buildok\exceptions\ValidatorException;

/**
*
*/
class Validator
{
	/**
	 * Create validator
	 * @param  string 	$type  Type of validator
	 * @param  mixed 	$value Value to test
	 * @param  array  	$rules Array of validation rules
	 * @return BaseValidator	Object of validator
	 *
	 * @throws ValidatorException
	 */
	public static function create($type, $value, $rules = [])
	{
        $class = 'buildok\\validator\\types\\' . ucfirst($type);
        if(!class_exists($class)) {
            throw new ValidatorException('Unknown validator ' . $type);
        }

        $validator = new $class($value, new ArrayWrapper($rules));

        return $validator;
	}
}