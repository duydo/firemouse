<?php
/**
 * @(#)ApplicationConfig.php Mar 4, 2009 1:46:54 PM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * ApplicationConfig class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage config
 * @version $Id: ApplicationConfig.php 16 2009-03-08 06:01:11Z duydo $
 */

class ApplicationConfig {
	const ACTION_EXTENSION = 'Action.class.php';
	const ACTIONS_EXTENSION = 'Actions.class.php';
	/**
	 * @var string
	 */
	const WEB_ROOT = 'webapps';
	
	/**
	 * @var string
	 */
	private $appName;
	
	/**
	 * @var string
	 */
	private $moduleDir = 'modules';
	/**
	 * @var string
	 */
	private $actionDir = 'actions';
	/**
	 * @var string
	 */
	private $serviceDir = 'services';
	/**
	 * @var string
	 */
	private $viewDir = 'views';

	/**
	 * @param string $moduleName
	 * @return string or null if path not found
	 */
	public function getModulePath($moduleName) {
		$path = $this->appName . DIRECTORY_SEPARATOR . $this->moduleDir . DIRECTORY_SEPARATOR . $moduleName;
		if ($this->pathExists($path)) {
			return $path;
		}
		return null;
	}

	/**
	 * Gets full path of action.
	 *
	 * @param string $actionName
	 * @param string $moduleName
	 * @return string or null if not found
	 */
	public function getActionPath($actionName, $moduleName) {
		$path = $this->getModulePath($moduleName);
		if ($path !== null) {
			$actionFile = $path . DIRECTORY_SEPARATOR . $this->actionDir . DIRECTORY_SEPARATOR . $actionName . self::ACTION_EXTENSION;
			if (!$this->pathExists($actionFile)) {
				$actionFile = $path . DIRECTORY_SEPARATOR . $this->actionDir . DIRECTORY_SEPARATOR . $actionName . self::ACTIONS_EXTENSION;
				if ($this->pathExists($actionFile)) {
					$path = $actionFile;
				}
			} else {
				$path = $actionFile;
			}
		}
		return $path;
	}

	/**
	 * Check if path exists or not.
	 *
	 * @param string $path
	 * @return boolean
	 */
	private function pathExists($path) {
		//FIXME
		return true;
	}

	/**
	 * @return string
	 */
	public function getActionDir() {
		return $this->actionDir;
	}

	/**
	 * @return string
	 */
	public function getAppName() {
		return $this->appName;
	}

	/**
	 * @return string
	 */
	public function getModuleDir() {
		return $this->moduleDir;
	}

	/**
	 * @return string
	 */
	public function getServiceDir() {
		return $this->serviceDir;
	}

	/**
	 * @return string
	 */
	public function getViewDir() {
		return $this->viewDir;
	}

	/**
	 * @param string $actionDir
	 */
	public function setActionDir($actionDir) {
		$this->actionDir = $actionDir;
	}

	/**
	 * @param string $appName
	 */
	public function setAppName($appName) {
		$this->appName = $appName;
	}

	/**
	 * @param string $moduleDir
	 */
	public function setModuleDir($moduleDir) {
		$this->moduleDir = $moduleDir;
	}

	/**
	 * @param string $serviceDir
	 */
	public function setServiceDir($serviceDir) {
		$this->serviceDir = $serviceDir;
	}

	/**
	 * @param string $viewDir
	 */
	public function setViewDir($viewDir) {
		$this->viewDir = $viewDir;
	}

}

