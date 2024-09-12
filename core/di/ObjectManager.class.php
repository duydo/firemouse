<?php
/**
 * @(#)ObjectManager.class.php Mar 19, 2009 11:29:50 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * ObjectManager class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

interface ObjectManager {

	/**
	 *@return ObjectFactory an instance of {@see ObjectFactory}
	 */
	public function getObjectFactory();

	/**
	 * Registers an object with specified name.
	 * 
	 * @param string $objectName the unique identifier of the object
	 * 				or the full object class name (including the package name) if $objectClassName is null
	 * @param string $objectClassName the full object class name (including the package name)
	 * @throws InvalidArgumentException if object name not valid
	 * @throws ObjectAlreadyExistedException if the object name already registered
	 */
	public function registerObject($objectName, $objectClassName = null);

	/**
	 * Removes an registered object with specified name.
	 *
	 * @param string $objectName the object name to remove
	 * @return object the object registered with this name or null if not found
	 */
	public function unregisterObject($objectName);

	/**
	 * Gets an instance of object with registered name.
	 *
	 * @param string $objectName the object name to get
	 * @return object the object instance or null if object not found
	 */
	public function getObject($objectName);

	/**
	 * Check if contains object with specified name.
	 * @param string $objectName
	 */
	public function contains($objectName);

}
