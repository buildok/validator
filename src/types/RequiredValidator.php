<?php
namespace buildok\validator\types;

use buildok\validator\types\base\BaseValidator;

/**
 *
 */
class RequiredValidator extends BaseValidator
{
	/**
	 * @inheritdoc
	 */
	protected function messages()
	{
		return [
			'is required',
		];
	}

	/**
	 * @inheritdoc
	 */
	protected function checkIt()
	{
		return !is_null($this->value);
	}
}