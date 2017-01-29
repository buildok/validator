<?php
namespace buildok\validator\types\base;

/**
 *
 */
abstract class BaseValidator
{
	/**
	 * Validation options
	 * @var buildok\helpers\ArrayWrapper
	 */
	protected $options;

	/**
	 * Array of validation errors codes
	 * @var array
	 */
	private $errors;

	/**
	 * Returns TRUE if it has errors else FALSE
	 * @return boolean
	 */
	public function hasErrors()
	{
		return (bool)$this->errors;
	}

	/**
	 * Returns validation errors
	 * @return array
	 */
	public function getErrors()
	{
		if ($msg = $this->options->message) {
			$errors[] = $msg;
		} else {

			$messages = $this->messages();
			foreach ($this->errors as $code) {
				$errors[] = $messages[$code];
			}
		}

		return $errors;
	}

	/**
	 * Main validation function
	 * @param  mixed $value Value to check
	 * @param  buildok\helpers\ArrayWrapper $options Validation options
	 * @return boolean
	 */
	public function validate($value, $options)
	{
		$this->value = $value;
		$this->options = $options;
		$this->errors = [];

		return $this->checkIt();
	}

	/**
	 * Check value
	 * @return boolean
	 */
	abstract protected function checkIt();

	/**
	 * Returns error messages
	 * @return array
	 */
	protected function messages()
	{
		return [];
	}

	/**
	 * Sets error code
	 * @param  int $code Key of error message
	 */
	protected function error($code)
	{
		if (!in_array($code, $this->errors)) {
			$this->errors[] = $code;
		}
	}
}