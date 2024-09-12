<?php
/**
 * @(#)Inflector.class.php Mar 21, 2009 2:49:39 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Inflector class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class Inflector {

	/**
	 *
	 * @param string $string
	 */
	public static function camelize($string) {
		return str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $string)));
	}
}
