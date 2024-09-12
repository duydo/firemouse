<?php
/**
 * @(#)FireMouseException.class.php Mar 20, 2009 1:46:37 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FireMouseException class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class FireMouseException extends Exception {
	/**
	 * @var Exception
	 */
	private $wrappedException = null;

	public static function wrapException(Exception $e) {
		$exception = new FireMouseException($e->getMessage(), $e->getCode());
		$exception->setWrappedException($e);
		return $exception;
	}

	public function setWrappedException(Exception $e) {
		$this->wrappedException = $e;
	}

	public function printStackTrace() {
		$e = ($wrappedException === null) ? $this : $wrappedException;
	}

}
