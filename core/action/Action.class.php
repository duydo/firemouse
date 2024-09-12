<?php
/**
 * @(#)Action.class.php Mar 20, 2009 11:18:16 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
ClassLoader::import('firemouse::core::action::Executable');
/**
 * Action class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

abstract class Action implements Executable {
	const SUCCESS = 'success';
	const INPUT = 'input';
	const ERROR = 'error';
	/**
	 * @var WebRequest
	 */
	protected $request;
	/**
	 * @var WebResponse
	 */
	protected $response;

	/**
	 * Override this method in your action.
	 * 
	 * @see Executable::execute()
	 * @return string
	 */
	public function execute() {
		throw new UnsupportedOperationException();
	}

	/**
	 * @param Request $request
	 */
	public final function injectRequest(Request $request) {
		$this->request = $request;
	}

	/**
	 * @param Response $response
	 */
	public final function injectResponse(Response $response) {
		$this->response = $response;
	}
}
