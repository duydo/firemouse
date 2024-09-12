<?php
/**
 * @(#)DefinitionManager.class.php Mar 14, 2009 1:52:34 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * DefinitionManager class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class DefinitionManager {
	
	private static $instance;
	private $defConfigs = array();
	private $defInstances = array();

	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function initialize($defConfigs = array()) {
		$this->defConfigs = $defConfigs;
	}

	public function add(Definition $definition) {
		$this->defInstances [$definition->getName()] = $definition;
	}

	public function get($name) {
		if (!array_key_exists($name, $this->defConfigs)) {
			return null;
		}
		
		if (!$this->contains($name)) {
			$def = $this->createDef($name, $this->defConfigs [$name]);
			$this->add($def);
		}
		return $this->defInstances [$name];
	}

	public function contains($name) {
		return array_key_exists($name, $this->defInstances);
	}

	public function getParent(Definition $definition) {
		return $this->get($definition->getParent());
	}

	protected function createDef($defName, $defProperties) {
		$def = new Definition($defName);
		if (array_key_exists(Definition::KEY_PARENT, $defProperties)) {
			$def->setParent($defProperties [Definition::KEY_PARENT]);
		}
		
		if (array_key_exists(Definition::KEY_TEMPLATE, $defProperties)) {
			$def->setTemplate($defProperties [Definition::KEY_TEMPLATE]);
		}
		
		if (array_key_exists(Definition::KEY_ATTRIBUTE, $defProperties)) {
			$def->setAttributes($defProperties [Definition::KEY_ATTRIBUTE]);
		}
		return $def;
	}
}
