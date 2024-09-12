<?php
/**
 * @(#)Definition.class.php Mar 14, 2009 1:43:53 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Definition class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class Definition {
	const KEY_TEMPLATE = 'template';
	const KEY_PARENT = 'extends';
	const KEY_ATTRIBUTE = 'attributes';
	
	/* Defintion name */
	private $name;
	/* Template file */
	private $template;
	/* Name extended */
	private $parent;
	/* Attributes */
	private $attributes = array();

	public function __construct($name = null, $template = null, $parent = null, $attributes = array()) {
		$this->name = $name;
		$this->template = $template;
		$this->parent = $parent;
		$this->attributes = $attributes;
	}

	public function hasTemplate() {
		return !is_null($this->template);
	}

	public function hasParent() {
		return !is_null($this->parent);
	}

	public function hasAttributes() {
		return count($this->attributes) != 0;
	}

	public function isParent() {
		return !$this->hasParent() && $this->hasTemplate();
	}

	/**
	 * @return attribute array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @return parent name definition
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return definition name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return template file
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * @param Array $attributes
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

	/**
	 * Set attribute for this definition.
	 *
	 * @param String $name
	 * @param String $value
	 */
	public function setAttribute($name, $value) {
		if (!empty($name)) {
			$this->attributes [$name] = $value;
		}
	}

	/**
	 * @param String $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * @param String $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param String $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	public function __toString() {
		$attributes = '[';
		foreach($this->attributes as $name => $value) {
			$attributes .= "$name=$value, ";
		}
		$attributes .= ']';
		return "definition[name=$this->name, template=$this->template, parent=$this->parent, attributes$attributes]";
	}
}
