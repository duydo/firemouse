<?php
/**
 * @(#)WebApplication.class.php Mar 18, 2009 8:39:36 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */
require_once dirname(__FILE__) . '/ClassLoader.class.php';
ClassLoader::fastAddIncludePath(array('.', '../lib', '../webapps'));
ClassLoader::import('firemouse::core::controller::AbstractController');
ClassLoader::import('firemouse::core::filter::DefaultFilterChain');
ClassLoader::import('firemouse::core::filter::FilterManager');
ClassLoader::import('firemouse::core::di::ObjectFactory');
ClassLoader::import('firemouse::core::di::ObjectManagerImpl');

/**
 * WebApplication class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class WebApplication extends AbstractController {
	
	/**
	 * @var string
	 */
	private $applicationName;
	/**
	 * @var ApplicationConfig
	 */
	private $applicationConfig;
	/**
	 * Applicaton environment: dev/prod/test
	 * @var string
	 */
	private $environment = 'prod';
	/**
	 * @var ObjectFactory
	 */
	private $objectFactory;
	
	/**
	 * @var FilterManager
	 */
	private $filterManager;

	/**
	 * @param string $applicationName the web application applicationName
	 * @param string $environment the environment prod/dev/test
	 */
	public function __construct($applicationName = 'application', $environment = 'prod') {
		$this->applicationName = $applicationName;
		$this->environment = $environment;
	}

	/**
	 * @return FilterChain
	 */
	public function getFilterChain() {
		return $this->filterManager->getFilterChain();
	}

	/**
	 * Runs application.
	 */
	public function run() {
		try {
			$this->filterManager->initialize();
			$this->applicationConfig->setAppName($this->applicationName);
			$request = $this->objectManager->getObject('request');
			$response = $this->objectManager->getObject('response');
			$this->filterManager->doFilter($request, $response);
		} catch(Exception $e) {
			$this->exceptionCaught($e);
		}
	}

	/**
	 * @return ApplicationConfig
	 */
	public function getConfig() {
		return $this->applicationConfig;
	}

	/**
	 * Exception caught.
	 *
	 * @param Exception $e
	 */
	protected function exceptionCaught(Exception $e) {
		if ($this->environment === 'dev') {
			echo $e->getMessage();
		} else {
			echo 'Service unavailable';
		}
	}

	/**
	 * Initialize web application.
	 */
	public final function initialize() {
		$this->initializeObjectFactory();
		$this->initializeObjectManager();
		$this->initializeFilterManager();
		$this->registerCoreObjects();
	}

	/**
	 * Initialize ObjectFactory.
	 */
	protected function initializeObjectFactory() {
		$this->objectFactory = new DefaultObjectFactory();
	}

	/**
	 * Initialize ObjectManager.
	 */
	protected function initializeObjectManager() {
		$this->objectManager = new ObjectManagerImpl();
		if ($this->objectFactory !== null) {
			$this->objectManager->injectObjectFactory($this->objectFactory);
		}
	}

	/**
	 * Initialize FilterManager.
	 */
	protected function initializeFilterManager() {
		$filterChain = new DefaultFilterChain();
		$filterChain->injectController($this);
		$this->filterManager = new FilterManager();
		$this->filterManager->injectFilterChain($filterChain);
	}

	/**
	 * Initialize FilterManager.
	 */
	protected function registerCoreObjects() {
		$this->objectManager->registerObject('request', 'firemouse::core::request::WebRequest');
		$this->objectManager->registerObject('response', 'firemouse::core::response::WebResponse');
		$this->objectManager->registerObject('applicationController', 'firemouse::core::controller::ApplicationController');
		$this->objectManager->registerObject('applicationConfig', 'firemouse::core::config::ApplicationConfig');
		$this->applicationConfig = $this->objectManager->getObject('applicationConfig');
	}
}
