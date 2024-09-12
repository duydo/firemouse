<?php
/**
 * @(#)FilterChain.php Mar 4, 2009 9:16:34 AM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

/**
 * FilterChain Interface.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage filter
 * @version $Id: FilterChain.php 16 2009-03-08 06:01:11Z duydo $
 */
interface FilterChain {

	/**
	 * Causes the next filter in the chain to be invoked, or if the calling filter is the last filter
	 * in the chain, causes the resource at the end of the chain to be invoked.
	 * @param Request $request the request to pass along the chain
	 * @param Response $response the response to pass along the chain
	 */
	public function doFilter(Request $request, Response $response);

	/**
	 * Adds the specified filter with the specified name at the beginning of this chain.
	 * @param String $name the name of filter
	 * @param Filter $filter the filter to add
	 * @throws <code>FilterException</code> if filter name has already existed
	 */
	public function addFirst($filterName, Filter $filter);

	/**
	 * Adds the specified filter with the specified name at the end of this chain.
	 * @param String $name the name of filter
	 * @param Filter $filter the filter to add
	 * @throws <code>FilterException</code> if filter name has already existed
	 */
	public function addLast($filterName, Filter $filter);

	/**
	 * Adds the specified filter with the specified name just after the filter whose name is
	 * <code>baseName</code> in this chain.
	 *
	 * @param String $baseFilterName the filter's name to add after
	 * @param String $name the filter name
	 * @param Filter $filter the filter to add
	 * @throws <code>FilterException</code> if filter name has already existed or base filter name not found
	 */
	public function addAfter($baseFilterName, $filterName, Filter $filter);

	/**
	 * Adds the specified filter with the specified name just before the filter whose name is
	 * <code>baseFilterName</code> in this chain.
	 *
	 * @param string $baseFilterName the filter's name to add before
	 * @param string $name the filter name
	 * @param Filter $filter the filter to add
	 * @throws <code>FilterException</code> if filter name has already existed or base filter name not found
	 */
	public function addBefore($baseFilterName, $filterName, Filter $filter);

	/**
	 * Replace the filter with the specified name with the specified new
	 * filter.
	 * @param string $filterName the filter name to replace
	 * @return the old filter
	 */
	public function replace($filterName, Filter $newFilter);

	/**
	 * Remove the filter with the sepecified
	 * @param string $filterName the filter name to remove
	 */
	public function remove($filterName);

	/**
	 * Returns <code>true</code> if this chain contains an {@link Filter} with the
	 * specified <tt>name</tt>.
	 * @param string $filterName the filter name to check
	 * @return boolean true or false if this chain does not contain
	 */
	public function contains($filterName);

	/**
	 * Returns filter object with specified name.
	 *
	 * @param string $filterName the filter name
	 * @return Filter or null
	 */
	public function getFilter($filterName);

	/**
	 * Removes all filters added to this chain.
	 */
	public function clear();
}


