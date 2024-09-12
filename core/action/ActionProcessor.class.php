<?php
/**
 * @(#)ActionProcessor.class.php Mar 20, 2009 3:45:16 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ActionProcessor class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage 
 * @version $Id$
 */

class ActionProcessor {

	public function invoke(Action $action) {
		return $action->execute();
	}
}
