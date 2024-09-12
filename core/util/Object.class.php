<?php
/**
 * @(#)Object.class.php Mar 21, 2009 11:15:31 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Object class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class Object {

	public static function newObject($className) {
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
