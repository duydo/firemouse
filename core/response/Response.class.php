<?php
/**
 * @(#)Response.php Mar 4, 2009 9:05:54 AM
 * Copyright (C) 2008 Duy Do. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * @package firemouse
 */

/**
 * Response abstract class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage reponse
 * @version $Id: Response.php 16 2009-03-08 06:01:11Z duydo $
 */
abstract class Response {
	/**
	 * @var string the response content
	 */
	protected $content = null;

	/**
	 * Send content to client
	 */
	public function send() {
		if (null !== $this->content) {
			echo $this->getContent();
		}
	}

	/**
	 * Set content for this response.
	 *
	 * @param string $content the content to set
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Returns content of this response.
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Appends content to the already existing content.
	 *
	 * @param string $content the content to append
	 */
	public function append($content) {
		$this->content .= $content;
	}

	/**
	 * Clear content.
	 */
	public function clear() {
		$this->content = null;
	}
}

