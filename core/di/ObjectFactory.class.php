<?php
/**
 * @(#)ObjectFactory.class.php Mar 18, 2009 9:37:54 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * ObjectFactory class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

interface ObjectFactory {

	/**
	 * Creates an instance of specified object name.
	 *
	 * @param string $className the class name (including package name)
	 */
	public function createObject($className);

}

class DefaultObjectFactory implements ObjectFactory {

	/**
	 * 
	 * @see ObjectFactory::createObject()
	 */
	public function createObject($className) {
		if (!is_string($className) || strlen(trim($className)) == 0) {
			return null;
		}
		
		$simpleClassName = $className;
		if (false !== strpos($simpleClassName, ClassLoader::PACKAGE_SEPARATOR)) {
			$classInfo = explode(ClassLoader::PACKAGE_SEPARATOR, $className);
			$simpleClassName = end($classInfo);
		}
		ClassLoader::import($className);
		return new $simpleClassName();
	}
}