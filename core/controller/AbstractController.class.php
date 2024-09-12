<?php
/**
 * @(#)AbstractController.php Mar 6, 2009 2:21:45 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */
ClassLoader::import('firemouse::core::controller::Controller');
/**
 * AbstractController class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage controller
 * @version $Id$
 */

abstract class AbstractController implements Controller {
	/**
	 * @var ObjectManager
	 */
	protected $objectManager;
	/**
	 * @var WebRequest
	 */
	protected $request;
	/**
	 * @var WebResponse
	 */
	protected $response;
	
	protected static $supportedRequestList = array('WebRequest');

	/**
	 * @see Controller::canExecute()
	 *
	 * @param Request $request
	 */
	public function canExecute(Request $request) {
		$executable = false;
		foreach(self::$supportedRequestList as $supportedRequest) {
			if ($request instanceof $supportedRequest) {
				$executable = true;
				break;
			}
		}
		return $executable;
	}

	/**
	 * @see Controller::execute()
	 *
	 * @param Request $request
	 * @param Response $reponse
	 */
	public function execute(Request $request, Response $response) {
		if (!$this->canExecute($request)) {
			ClassLoader::import('firemouse::core::exception::UnsupportedRequestException');
			throw new UnsupportedRequestException('This request type not supported');
		}
		$this->request = $request;
		$this->response = $response;
		$applicationController = $this->objectManager->getObject('applicationController');
		$applicationController->injectObjectManager($this->objectManager);
		$applicationController->dispatch($request, $response);
	}

	/**
	 *  Forwards the request to another action/module.
	 *
	 * @param string $actionName
	 * @param string $moduleName if this parameter not specified, the current module is used
	 */
	public function forward($actionName, $moduleName = null) {
		$this->request->setActionName($actionName);
		if (null !== $moduleName) {
			$this->request->setModuleName($moduleName);
		}
		$this->objectManager->getObject('applicationController')->dispatch($this->request, $this->response);
	}

	/**
	 * Redirect to uri.
	 *
	 * @param string $uri
	 * @param int $delay
	 * @param int $statusCode
	 */
	public function redirect($uri, $delay = 0, $statusCode = 302) {
		$escapedUri = htmlentities($uri, ENT_QUOTES, 'utf-8');
		$content = sprintf('<html><head><meta http-equiv="refresh" content="%s;url=%s"/></head></html>', $delay, $escapedUri);
		$this->response->setContent($content);
		$this->response->setStatusCode($statusCode);
		$this->response->setHeader('Location', $uri);
		$this->response->send();
	}

}
