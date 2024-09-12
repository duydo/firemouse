<?php
/**
 * @(#)Controller.class.php Mar 18, 2009 9:51:36 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Controller class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

interface Controller {

	/**
	 * Check if the request type supported or not
	 *
	 * @param Request $request
	 */
	public function canExecute(Request $request);

	/**
	 * Execute the request.
	 * 
	 * @param Request $request
	 * @param Response $response
	 * @throws <code>UnsupportedRequestException</code> if the request type not supported
	 */
	public function execute(Request $request, Response $response);
}
