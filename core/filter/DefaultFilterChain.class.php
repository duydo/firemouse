<?php
/**
 * @(#)DefaultFilterChain.php Mar 4, 2009 9:32:21 AM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */
ClassLoader::import('firemouse::core::filter::FilterChain');
ClassLoader::import('firemouse::core::exception::FilterException');
/**
 * Default implement for FilterChain interface.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage filter
 * @version $Id: DefaultFilterChain.php 16 2009-03-08 06:01:11Z duydo $
 */

class DefaultFilterChain implements FilterChain {
	private $filterMap = array();
	private $filterOrderList = array();
	private $index = -1;
	
	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * @param Controller $controller
	 */
	public function injectController(Controller $controller) {
		$this->controller = $controller;
	}

	/**
	 * @see FilterChain::doFilter()
	 */
	public function doFilter(Request $request, Response $response) {
		++$this->index;
		if ($this->index < count($this->filterOrderList)) {
			$filterName = $this->filterOrderList [$this->index];
			$filter = $this->filterMap [$filterName];
			$filter->doFilter($request, $response, $this);
		} else {
			$this->controller->execute($request, $response);
		}
	}

	/**
	 * @see FilterChain::addFirst()
	 */
	public function addFirst($filterName, Filter $filter) {
		$this->checkName($filterName);
		$this->addAt(0, $filterName, $filter);
	}

	/**
	 * @see FilterChain::addLast()
	 */
	public function addLast($filterName, Filter $filter) {
		$this->checkName($filterName);
		$this->filterOrderList [] = $filterName;
		$this->filterMap [$filterName] = $filter;
	}

	/**
	 * @see FilterChain::addAfter()
	 */
	public function addAfter($baseFilterName, $filterName, Filter $filter) {
		$index = array_search($baseFilterName, $this->filterOrderList);
		if ($index === false) {
			throw new FilterException(sprintf('The filter name "%s" not found', $baseFilterName));
		}
		$this->addAt($index + 1, $filterName, $filter);
	}

	/**
	 * @see FilterChain::addBefore()
	 */
	public function addBefore($baseFilterName, $filterName, Filter $filter) {
		$index = array_search($baseFilterName, $this->filterOrderList);
		if ($index === false) {
			throw new FilterException(sprintf('The filter name "%s" not found', $baseFilterName));
		}
		$this->addAt($index, $filterName, $filter);
	}

	/**
	 * @see FilterChain::replace()
	 */
	public function replace($filterName, Filter $newFilter) {
		$oldFilter = $this->filterMap [$filterName];
		$this->filterMap [$filterName] = $newFilter;
		return $oldFilter;
	}

	/**
	 * @see FilterChain::remove()
	 */
	public function remove($filterName) {
		$index = array_search($filterName, $this->filterOrderList);
		if ($index === false) {
			return null;
		}
		$oldFilter = $this->filterMap [$filterName];
		if ($index == 0) {
			array_shift($this->filterOrderList);
		} else {
			if ($index == count($this->filterOrderList)) {
				$this->filterOrderList = array_slice($this->filterOrderList, 0, $index);
			} else {
				$head = array_slice($this->filterOrderList, 0, $index);
				$tail = array_slice($this->filterOrderList, $index + 1);
				$this->filterOrderList = array_merge($head, $tail);
			}
		}
		// Remove filter from map
		unset($this->filterMap [$filterName]);
		return $oldFilter;
	}

	/**
	 * @see FilterChain::contains()
	 */
	public function contains($filterName) {
		return array_key_exists($filterName, $this->filterOrderList);
	}

	/**
	 * @see FilterChain::getFilter()
	 */
	public function getFilter($filterName) {
		if ($this->contains($filterName)) {
			return $this->filterMap [$filterName];
		}
		return null;
	}

	/**
	 * @see FilterChain::clear()
	 */
	public function clear() {
		$this->filterOrderList = array();
		$this->filterMap = array();
	}

	// Helper
	private function checkName($filterName) {
		if (!is_string($filterName) || strlen(trim($filterName)) == 0) {
			throw new FilterException(sprintf('The filter name must be non-empty string: "%s"', $filterName));
		}
		
		if ($this->contains($filterName)) {
			throw new FilterException(sprintf('The filter name "%s" already existed', $filterName));
		}
	}

	private function addAt($index, $filterName, Filter $filter) {
		$this->checkName($filterName);
		array_splice($this->filterOrderList, $index, 0, $filterName);
		$this->filterMap [$filterName] = $filter;
	}

}

