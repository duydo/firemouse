<?php
/**
 * @(#)View.class.php Mar 14, 2009 2:34:50 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * View class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class View {
	private $dm;
	private $data = array();

	public function __construct() {
		$this->dm = DefinitionManager::getInstance();
	}

	public function __set($var, $value) {
		$this->bind($var, $value);
	}

	public function bind($var, $value) {
		$this->data [$var] = $value;
	}

	public function render($page, $returned = false) {
		$def = $this->getParentDefinition($page);
		if ($returned) {
			return $this->renderDefinition($def, true);
		} else {
			$this->renderDefinition($def, false);
		}
	}

	/**
	 * Render a definition.
	 *
	 * @param Definition $def
	 * @param bool $returned set true if you want to return content
	 * @return definition's content if $returned is true, otherwise  void 
	 */
	public function renderDefinition(Definition $def = null, $returned = false) {
		if ($def === null) {
			throw new Exception('The definition is null');
		}
		if (!$def->hasTemplate()) {
			throw new Exception(sprintf('The definition "%s" has no template.', $def->getName()));
		}
		//Fetch attributes contents
		if ($def->hasAttributes()) {
			$attributes = $def->getAttributes();
			$this->fetchContentForAttributes($attributes);
		}
		
		//Render template
		$template = $def->getTemplate();
		if ($returned) {
			return $this->fetch($template, $attributes, true);
		} else {
			$this->fetch($template, $attributes);
		}
	}

	protected function fetchContentForAttributes(&$attributes) {
		foreach($attributes as $name => $value) {
			if (array_key_exists($name, $this->data)) {
				//Override attribute content with binding content
				$attributes [$name] = $this->data [$name];
				continue;
			}
			$pos = strstr($value, '.php');
			if ($pos !== false) {
				// Attribute is a template
				$attributes [$name] = $this->fetch($value, $this->data, true);
			} else {
				if ($this->dm->contains($value)) {
					// Attribute is a definition
					$attributeIsDef = $this->dm->get($value);
					$attributes [$name] = $this->renderDefinition($attributeIsDef, true);
				}
			}
		
		}
	}

	protected function fetch($file, $data = array(), $returned = false) {
		extract($data);
		ob_start();
		include $file;
		if ($returned) {
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		} else {
			ob_end_flush();
		}
	}

	private function getParentDefinition($name) {
		$def = $this->dm->get($name);
		
		if (null === $def) {
			return null;
		}
		
		if ($def->isParent()) {
			return $def;
		}
		
		if ($def->hasParent()) {
			$parentDef = $this->getParentDefinition($def->getParent());
			
			if (null === $parentDef) {
				return null;
			}
			
			if ($def->hasTemplate()) {
				//Override template
				$parentDef->setTemplate($def->getTemplate());
			}
			
			if ($def->hasAttributes()) {
				//Override attributes
				$defAttributes = $def->getAttributes();
				if ($parentDef->hasAttributes()) {
					$parentAttributes = $parentDef->getAttributes();
					$attributes = array_merge($parentAttributes, $defAttributes);
					$parentDef->setAttributes($attributes);
				} else {
					$parentDef->setAttributes($defAttributes);
				}
			}
			return $parentDef;
		}
		return null;
	}
}

