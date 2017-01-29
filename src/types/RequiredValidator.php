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
		if ($ret = is_null($this->value)) {
			$this->error(0);
		}

		return !$ret;
	}
}