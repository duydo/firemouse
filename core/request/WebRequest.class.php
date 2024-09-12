<?php
/**
 * @(#)WebRequest.class.php Mar 18, 2009 10:27:20 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

ClassLoader::import('firemouse::core::request::Request');
ClassLoader::import('firemouse::core::util::NameValuePair');

/**
 * WebRequest class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class WebRequest extends Request {
	/**
	 * @var NameValuePair the parameters holder
	 */
	protected $parameters;
	
	/**
	 * @var NameValuePair the attributes holder
	 */
	protected $attributes;

	public function __construct() {
		$this->initialize();
	}

	/**
	 * Inititializes this request.
	 */
	public function initialize() {
		$this->parameters = new NameValuePair();
		$this->attributes = new NameValuePair();
		$this->parameters->add($_GET);
		$this->parameters->add($_POST);
	}

	//Query operations
	

	/**
	 * Returns an array containing names of parameters available to this request. 
	 *
	 * @return array an array containing names of parameters
	 */
	public function getParameterNames() {
		return $this->parameters->getNames();
	}

	/**
	 * Returns an array containing the values of parameters available to this request. 
	 *
	 * @return array an array containing values of parameters
	 */
	public function getParameterValues() {
		return $this->parameters->getValues();
	}

	/**
	 * Returns the value of a request parameter.
	 *
	 * @param string $name the parameter name
	 * @return string or null if the parameter does not exist
	 */
	public function getParameter($name) {
		return $this->parameters->get($name);
	}

	/**
	 * Returns an array containing the names of the attributes available to this request.
	 *
	 * @return array an array contains names of attributes
	 */
	public function getAttributeNames() {
		return $this->attributes->getNames();
	}

	/**
	 * Returns the value of the named attribute.
	 *
	 * @param string $name
	 * @return mixed a mixed value of an attribute or null
	 */
	public function getAttribute($name) {
		$this->attributes->get($name);
	}

	// Bulk operations
	

	/**
	 * Check if the request contains an attribute name.
	 *
	 * @param string $name the attribute name
	 * @return a bool true or false
	 */
	public function hasAttribute($name) {
		return $this->attributes->contains($name);
	}

	/**
	 * Check if the request contains a parameter name.
	 *
	 * @param string $name the parameter name
	 * @return bool a bool true or false
	 */
	public function hasParameter($name) {
		return $this->parameters->contains($name);
	}

	// Modify operations
	

	/**
	 * Set the value for a parameter name.
	 *
	 * @param string $name the name to set
	 * @param string $value the value to set
	 * @throws InvalidArgumentException if the parameter name or the value is not a string
	 */
	public function setParameter($name, $value) {
		if (!is_string($name) || !is_string($value)) {
			throw new InvalidArgumentException('The parameter name or the value must be a string');
		}
		$this->parameters->set($name, $value);
	}

	/**
	 * Sets parameters with specified array
	 *
	 * @param array $parameters
	 */
	protected function setParamerters(array $parameters) {
		$this->parameters->add($parameters);
	}

	/**
	 * Set the value for an attribute name.
	 *
	 * @param string $name the name to set
	 * @param string $value the value to set
	 * @throws InvalidArgumentException if the attribute name is not a string
	 */
	public function setAttribute($name, $value) {
		if (!is_string($name)) {
			throw new InvalidArgumentException('The attribute name must be a string');
		}
		$this->attributes->set($name, $value);
	}

	/**
	 * Sets attributes with specified array
	 *
	 * @param array $parameters
	 */
	protected function setAttributes(array $attributes) {
		$this->attributes->add($attributes);
	}

	/**
	 * Remove the parameter with specified name from this request.
	 *
	 * @param string $name the parameter name to remove
	 * @return string a string value of the parameter
	 */
	public function removeParameter($name) {
		return $this->parameters->remove($name);
	}

	/**
	 * Remove the attribute with specified name from this request.
	 *
	 * @param string $name the attribute name to remove
	 * @return mixed a mixed value of the attribute
	 */
	public function removeAttribute($name) {
		return $this->attributes->remove($name);
	}
}
