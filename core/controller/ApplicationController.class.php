<?php
/**
 * @(#)ApplicationController.class.php Mar 20, 2009 3:40:28 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
ClassLoader::import('firemouse::core::util::Inflector');
/**
 * ApplicationController to centralize retrieval and invocation of request-processing components
 * such as actions and views
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */
class ApplicationController {
	const MAX_DISPATCH_LOOP = 100;
	
	private static $dispatchCount = 0;
	/**
	 * @var ObjectManager
	 */
	private $om;
	/**
	 * @var Request
	 */
	protected $request;
	
	/**
	 * @var Response
	 */
	protected $response;

	public function injectObjectManager(ObjectManager $om) {
		$this->om = $om;
	}

	/**
	 * Dispatch request.
	 *
	 * @param Request $request
	 * @param Response $response
	 * @throws <code>ModuleNotFoundException</code> if module not found
	 * @throws <code>ActionNotFoundException</code> if action not found
	 */
	public function dispatch(Request $request, Response $response) {
		++self::$dispatchCount;
		if (self::$dispatchCount > self::MAX_DISPATCH_LOOP) {
			throw new ErrorException('Infinite loop');
		}
		
		$this->request = $request;
		$this->response = $response;
		
		$actionInstance = $this->lookupActionInstance();
		$actionResult = $actionInstance->execute();
		$view = $this->lookupView($actionResult);
		$response->append($view->render());
	}

	/**
	 * Lookup {@see Action} object.
	 * @param Request $request
	 * @throws <code>ModuleNotFoundException</code> if module not found
	 * @throws <code>ActionNotFoundException</code> if action not found
	 * @return object an instance of {@see Action}
	 */
	protected function lookupActionInstance() {
		$actionClass = $this->lookupActionClassName();
		$actionInstance = $this->om->getObjectFactory()->createObject($actionClass);
		$actionInstance->injectRequest($this->request);
		$actionInstance->injectResponse($this->response);
		return $actionInstance;
	}

	/**
	 * Look up {@see View} object.
	 *
	 * @param string $actionResult
	 * @return object an instance of {@see View}
	 */
	public function lookupView($actionResult) {
		//@FIXME for testing only
		return $this->om->getObjectFactory()->createObject('firemouse::core::view::View');
	}

	/**
	 * Lookup action class name.
	 * 
	 * @throws <code>ModuleNotFoundException</code> if module not found
	 * @throws <code>ActionNotFoundException</code> if action not found
	 * @return string a full class name, including package name
	 */
	protected function lookupActionClassName() {
		$moduleName = $this->request->getModuleName();
		$actionName = Inflector::camelize($this->request->getActionName());
		
		$config = $this->om->getObject('applicationConfig');
		if (null === $config->getModulePath($moduleName)) {
			ClassLoader::import('firemouse::core::exception::ModuleNotFoundException');
			throw new ModuleNotFoundException(sprintf('Module not found: "%s"', $moduleName));
		}
		$actionFile = $config->getActionPath($actionName, $moduleName);
		if (null === $actionFile) {
			ClassLoader::import('firemouse::core::exception::ModuleNotFoundException');
			throw new ActionNotFoundException(sprintf('Action not found: "%s"', $actionName));
		}
		$actionFile = substr($actionFile, 0, (strlen($actionFile) - strlen(ClassLoader::PHP_CLASS_EXTENSION)));
		return str_replace(DIRECTORY_SEPARATOR, ClassLoader::PACKAGE_SEPARATOR, $actionFile);
	}
}