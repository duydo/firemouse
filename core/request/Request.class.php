<?php
/**
 * @(#)Request.php Mar 4, 2009 9:04:53 AM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Request abstract class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage request
 * @version $Id: Request.php 16 2009-03-08 06:01:11Z duydo $
 */
abstract class Request {
	
	/**
	 * @var string
	 */
	protected $moduleName;
	/**
	 * @var string
	 */
	protected $actionName;

	/**
	 * @return string
	 */
	public function getActionMethodName() {
		return $this->actionMethodName;
	}

	/**
	 * @return string
	 */
	public function getActionName() {
		return $this->actionName;
	}

	/**
	 * @return string
	 */
	public function getModuleName() {
		return $this->moduleName;
	}

	/**
	 * @param string $actionName
	 */
	public function setActionName($actionName) {
		$this->actionName = $actionName;
	}

	/**
	 * @param string $moduleName
	 */
	public function setModuleName($moduleName) {
		$this->moduleName = $moduleName;
	}

}

