<?php
/**
 * @(#)RoutingFilter.class.php Mar 20, 2009 10:49:24 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
ClassLoader::import('firemouse::core::filter::FilterAdapter');
/**
 * RoutingFilter class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class RoutingFilter extends FilterAdapter {

	/**
	 * @see FilterAdapter::preFilter()
	 *
	 * @param Request $request
	 * @param Response $response
	 */
	public function preFilter(Request $request, Response $response) {
		$request->setModuleName($request->getParameter('module'));
		$request->setActionName($request->getParameter('action'));
		$response->append('Filter START');
	}

	/**
	 * @see FilterAdapter::postFilter()
	 *
	 * @param Request $request
	 * @param Response $response
	 */
	public function postFilter(Request $request, Response $response) {
		$response->append('Filter END');
	}

}
