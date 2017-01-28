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
	 * Error code
	 * @var string
	 */
	protected $code;

	/**
	 * Validation errors
	 * @var array
	 */
	private $errors;

	/**
	 * Init
	 */
	public function __construct()
	{
		$this->code = 0;
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
	 * Returns validation errors
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
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

		if (!$ret = $this->checkIt()) {
			$this->setError();
		}

		return $ret;
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
	 * Sets error message
	 */
	protected function setError()
	{
		if (!$msg = $this->options->message) {
			$msg = $this->messages()[$this->code];
		}

		$this->errors[] = $msg;
	}
}