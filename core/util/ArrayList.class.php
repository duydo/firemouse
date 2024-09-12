<?php
/**
 * @(#)ArrayList.php Mar 9, 2009 12:27:06 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * ArrayList class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class ArrayList {
	private $elements = array();
	private $size = 0;

	public function size() {
		return $this->size;
	}

	public function add($element) {
		$this->elements [$this->size++] = $element;
		return true;
	}

	public function setAt($index, $newElement) {
		$this->checkRange($index);
		$oldValue = $this->elements [$index];
		$this->elements [$index] = $newElement;
		return $oldValue;
	}

	public function insertAt($index, $element) {
		$this->checkRange($index);
		array_splice($this->elements, $index, 0, $element);
		++$this->size;
	}

	public function get($index) {
		$this->checkRange($index);
		return $this->elements [$index];
	}

	public function contains($element) {
		return false !== array_search($element, $this->elements);
	}

	public function removeAt($index) {
		$this->checkRange($index);
		$oldValue = $this->elements [$index];
		unset($this->elements [$index]);
		--$this->size;
		return $oldValue;
	
	}

	public function remove($element) {
		$index = array_search($element, $this->elements);
		if ($index !== false) {
			//Found $element
			unset($this->elements [$index]);
			--$this->size;
			return true;
		}
		return FALSE;
	}

	public function clear() {
		unset($this->elements);
		$this->elements = array();
		$this->size = 0;
	}

	public function toArray() {
		return $this->elements;
	}

	private function checkRange($index) {
		if ($index > $this->size || $index < 0) {
			throw new Exception(sprintf('Index %s out of bound', $index));
		}
	}
}
