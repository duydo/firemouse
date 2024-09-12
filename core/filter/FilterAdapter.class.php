<?php
/**
 * @(#)FilterAdapter.php Mar 8, 2009 11:57:11 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

ClassLoader::import('firemouse::core::filter::Filter');

/**
 * FilterAdapter class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage  filter
 * @version $Id$
 */

abstract class FilterAdapter implements Filter {

	/**
	 * @see Filter::doFilter()
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param FilterChain $filterChain
	 */
	public final function doFilter(Request $request, Response $response, FilterChain $filterChain) {
		$this->preFilter($request, $response);
		$filterChain->doFilter($request, $response);
		$this->postFilter($request, $response);
	}

	/**
	 * Override this method for filter incoming request.
	 *
	 * @param Request $request the web request
	 * @param Response $response the web response
	 */
	public function preFilter(Request $request, Response $response) {
	}

	/**
	 * Override this method for filter outgoinh response.
	 * 
	 * @param Request $request the web request
	 * @param Response $response the web response
	 */
	public function postFilter(Request $request, Response $response) {}
}
