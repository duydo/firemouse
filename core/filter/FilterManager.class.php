<?php
/**
 * @(#)FilterManager.php Mar 4, 2009 10:32:40 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */
ClassLoader::import('firemouse::core::filter::DefaultFilterChain');
ClassLoader::importPackage('firemouse::core::filter::supports');

/**
 * FilterManager class.
 * 
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage filter
 * @version $Id: FilterManager.php 16 2009-03-08 06:01:11Z duydo $
 */

class FilterManager {
	/**
	 * @var FilterChain
	 */
	private $filterChain;

	/**
	 * @param FilterChain $filterChain
	 */
	public function injectFilterChain(FilterChain $filterChain) {
		$this->filterChain = $filterChain;
	}

	/**
	 * Initialize.
	 */
	public function initialize() {}

	/**
	 * Starts to filter the request.
	 *
	 * @param Request $request
	 * @param Response $response
	 */
	public function doFilter(Request $request, Response $response) {
		$this->loadCoreFilters();
		$this->filterChain->doFilter($request, $response);
		$response->send();
	}

	/**
	 * @return FilterChain
	 */
	public function getFilterChain() {
		return $this->filterChain;
	}

	/**
	 * Loads core filters.
	 */
	private final function loadCoreFilters() {
		$this->filterChain->addLast('routing', new RoutingFilter());
	}

}
