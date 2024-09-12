<?php
/**
 * @(#)InjectorImpl.class.php Mar 19, 2009 11:59:21 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

require_once dirname(__FILE__) . '/ObjectManager.class.php';

/**
 * InjectorImpl class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class ObjectManagerImpl implements ObjectManager {
	private $objectInstances = array();
	/**
	 * @var ObjectFactory
	 */
	private $objectFactory = null;

	/**
	 * Inject object factory.
	 *
	 * @param ObjectFactory $objectFactory
	 */
	public function injectObjectFactory(ObjectFactory $objectFactory) {
		$this->objectFactory = $objectFactory;
	}

	/**
	 * @see ObjectManager::getObject()
	 *
	 * @param string $objectName
	 * @return object
	 */
	public function getObject($objectName) {
		if ($this->contains($objectName)) {
			return $this->objectInstances [$objectName];
		}
		return null;
	}

	/**
	 * @see ObjectManager::getObjectFactory()
	 *
	 * @return ObjectFactory
	 */
	public function getObjectFactory() {
		return $this->objectFactory;
	}

	/**
	 * @see ObjectManager::registerObject()
	 *
	 * @param string $objectName
	 * @param string $objectClassName
	 */
	public function registerObject($objectName, $objectClassName = null) {
		$this->ensureObjectNameValid($objectName);
		
		if ($this->contains($objectName)) {
			require_once dirname(__FILE__) . '/exception/ObjectAlreadyExistedException.class.php';
			throw new ObjectAlreadyExistedException(sprintf('Object name "%s" already registered', $objectName));
		}
		
		if (null == $objectClassName) {
			$objectClassName = $objectName;
		} else {
			$this->ensureObjectNameValid($objectClassName);
		}
		
		$this->objectInstances [$objectName] = $this->objectFactory->createObject($objectClassName);
	}

	/**
	 * @see ObjectManager::unregisterObject()
	 *
	 * @param string $objectName
	 */
	public function unregisterObject($objectName) {
		$oldValue = null;
		if ($this->contains($objectName)) {
			$oldValue = $this->objectInstances [$objectName];
			unset($this->objectInstances [$objectName]);
		}
		return $oldValue;
	}

	/**
	 * @see ObjectManager::contains()
	 *
	 * @param string $objectName
	 */
	public function contains($objectName) {
		return array_key_exists($objectName, $this->objectInstances);
	}

	private function ensureObjectNameValid($objectName) {
		if (!is_string($objectName) || strlen(trim($objectName)) == 0) {
			throw new InvalidArgumentException(sprintf('Object name must be non-empty string: "%s"', $objectName));
		}
	}
}
