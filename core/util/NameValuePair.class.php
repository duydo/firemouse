<?php
/**
 * @(#)NameValuePair.php Mar 6, 2009 9:48:28 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * NameValuePair class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage common
 * @version $Id$
 */

class NameValuePair implements Serializable {
	private $nameValuePairs = array();

	public function __construct() {}

	/**
	 * Add an array containing key/value pairs to this object.
	 * <p>
	 * If the added array containing keys has same names contained in this object, 
	 * the value of names will be replaced with the value of these keys.
	 * 
	 * @param array $array the array to add
	 * @return boolean a boolean true of false
	 */
	public function add(array $array = array()) {
		foreach($array as $key => $value) {
			$this->nameValuePairs [$key] = $value;
		}
	}

	/**
	 * Set a $value for a $name.
	 *
	 * @param string $name the name to set
	 * @param mixed $value the value to set
	 */
	public function set($name, $value) {
		$this->nameValuePairs [$name] = $value;
	}

	/**
	 * Returns a value of a specified name.
	 *
	 * @param string $name the name to get
	 * @param mixed $default the default value
	 * @return mixed a mixed value
	 */
	public function get($name, $default = null) {
		if (!$this->contains($name)) {
			return $default;
		}
		return $this->nameValuePairs [$name];
	}

	/**
	 * Returns an array containing all names available to this object.
	 *
	 * @return array an array of string
	 */
	public function getNames() {
		return array_keys($this->nameValuePairs);
	}

	/**
	 * Returns an array containing all values available to this object.
	 *
	 * @return array an array contains mixed value
	 */
	public function getValues() {
		return array_values($this->nameValuePairs);
	}

	/**
	 * Returns an array containing all name/value pairs of this object.
	 *
	 * @return array an array of name/value pairs
	 */
	
	public function getAll() {
		return $this->nameValuePairs;
	}

	/**
	 * Check if this object contains a specified name.
	 *
	 * @param string $name the name to check
	 * @return boolean a boolean true or false
	 */
	public function contains($name) {
		if (!is_string($name)) {
			return false;
		}
		return array_key_exists($name, $this->nameValuePairs);
	}

	/**
	 * Remove value of a specified name from this object.
	 *
	 * @param string $name the name to remove
	 * @return mixed value the value of specified name
	 */
	public function remove($name) {
		if ($this->contains($name)) {
			return null;
		}
		$oldValue = $this->nameValuePairs [$name];
		unset($this->nameValuePairs [$name]);
		return $oldValue;
	}

	/**
	 * Serializes this object.
	 *
	 * @return string a string contains byte-stream representation of
	 */
	public function serialize() {
		return serialize($this->nameValuePairs);
	}

	/**
	 * Creates a NameValuePair object  from a stored representation.
	 *
	 * @param string $serialized the byte-stream representation of this object.
	 */
	public function unserialize($serialized) {
		$this->nameValuePairs = unserialize($serialized);
	}
}
