<?php
namespace buildok\validator\types;

use buildok\validator\types\base\BaseValidator;

/**
 *
 */
class StringValidator extends BaseValidator
{
	/**
	 * @inheritdoc
	 */
	protected function messages()
	{
		return [
			'is should be string',
			"is too long string (max = $this->options->max)",
			"is too short string (min = $this->options->min)"
		];
	}

	/**
	 * @inheritdoc
	 */
	protected function checkIt()
	{
		if (!$ret = is_string($this->value)) {
			$this->error(0);
		} else {
			!$this->options->max || $ret && $this->maxLength();
			!$this->options->min || $ret && $this->minLength();
		}

		return $ret;
	}

	/**
	 * Check max length
	 * @return boolean
	 */
	private function maxLength()
	{
		$max = mb_strlen($this->value);
		if (!$ret = $max <= $this->options->max) {
			$this->error(1);
		}

		return $ret;
	}

	/**
	 * Check min length
	 * @return boolean
	 */
	private function minLength()
	{
		$min = mb_strlen($this->value);
		if (!$ret = $min >= $this->options->min) {
			$this->error(2);
		}

		return $ret;
	}
}