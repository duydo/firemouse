<?php
/**
 * @(#)Filter.php Mar 4, 2009 9:08:11 AM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Filter Interface.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage filter
 * @version $Id: Filter.php 16 2009-03-08 06:01:11Z duydo $
 */
interface Filter {

	/**
	 * Do Filter.
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param FilterChain $filterChain
	 */
	function doFilter(Request $request, Response $response, FilterChain $filterChain);
}
