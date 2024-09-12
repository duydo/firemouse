<?php
/**
 * @(#)WebResponse.php Mar 6, 2009 11:23:53 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

ClassLoader::import('firemouse::core::response::Response');

/**
 * WebResponse class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage response
 * @version $Id$
 */

class WebResponse extends Response {
	/**
	 * The HTTP headers which will be sent in the response.
	 *
	 * @var array
	 */
	protected $headers = array();
	/**
	 * Cookies will be sent in the response.
	 *
	 * @var array
	 */
	protected $cookies = array();
	
	protected $options = array();
	
	/**
	 * The HTTP status code.
	 *
	 * @var int
	 */
	protected $statusCode = 200;
	
	/**
	 * The HTTP status message.
	 *
	 * @var string
	 */
	protected $statusMessage = 'OK';
	
	protected static $statusMessages = array(
			100 => 'Continue', 
			101 => 'Switching Protocols', 
			102 => 'Processing',  # RFC 2518
			200 => 'OK', 
			201 => 'Created', 
			202 => 'Accepted', 
			203 => 'Non-Authoritative Information', 
			204 => 'No Content', 
			205 => 'Reset Content', 
			206 => 'Partial Content', 
			207 => 'Multi-Status', 
			300 => 'Multiple Choices', 
			301 => 'Moved Permanently', 
			302 => 'Found', 
			303 => 'See Other', 
			304 => 'Not Modified', 
			305 => 'Use Proxy', 
			307 => 'Temporary Redirect', 
			400 => 'Bad Request', 
			401 => 'Unauthorized', 
			402 => 'Payment Required', 
			403 => 'Forbidden', 
			404 => 'Not Found', 
			405 => 'Method Not Allowed', 
			406 => 'Not Acceptable', 
			407 => 'Proxy Authentication Required', 
			408 => 'Request Timeout', 
			409 => 'Conflict', 
			410 => 'Gone', 
			411 => 'Length Required', 
			412 => 'Precondition Failed', 
			413 => 'Request Entity Too Large', 
			414 => 'Request-URI Too Long', 
			415 => 'Unsupported Media Type', 
			416 => 'Requested Range Not Satisfiable', 
			417 => 'Expectation Failed', 
			500 => 'Internal Server Error', 
			501 => 'Not Implemented', 
			502 => 'Bad Gateway', 
			503 => 'Service Unavailable', 
			504 => 'Gateway Timeout', 
			505 => 'HTTP Version Not Supported', 
			507 => 'Insufficient Storage', 
			509 => 'Bandwidth Limit Exceeded');

	public function __construct() {
		$this->options ['charset'] = 'utf-8';
		$contentType = isset($this->options ['Content-Type']) ? $this->options ['Content-Type'] : 'text/html';
		$this->options ['Content-Type'] = $this->normalizeContentType($contentType);
	}

	/**
	 * @see Response::send()
	 */
	public function send() {
		$this->sendHeaders();
		parent::send();
	}

	/**
	 * Sends the HTTP headers.
	 *
	 * If headers have already been sent, do nothing.
	 */
	public function sendHeaders() {
		if (!headers_sent()) {
			// headers
			foreach($this->getHeaders() as $header) {
				header($header);
			}
			// cookies
			foreach($this->cookies as $cookie) {
				setrawcookie($cookie ['name'], $cookie ['value'], $cookie ['expire'], $cookie ['path'], $cookie ['domain'], $cookie ['secure'], $cookie ['httpOnly']);
			}
		}
	}

	/**
	 * Sets response status code.
	 *
	 * @param int $statusCode HTTP status code
	 * @param string $statusMessage HTTP status message
	 */
	public function setStatusCode($statusCode, $statusMessage = null) {
		$this->statusCode = $statusCode;
		$this->statusMessage = (null !== $statusMessage ? $statusMessage : self::$statusMessages [$statusCode]);
	}

	/**
	 * Sets the specified HTTP header.
	 *
	 * @param string $name the
	 * @param mixed $value
	 * @param boolean $replace if a header with the same name should be replaced; default is true
	 */
	public function setHeader($name, $value, $replace = true) {
		$name = $this->normalizeHeaderName($name);
		if ($replace === true || !isset($this->headers [$name])) {
			$this->headers [$name] = array($value);
		} else {
			$this->headers [$name] [] = $value;
		}
	}

	/**
	 * Returns HTTP headers.
	 *
	 * @return array
	 */
	public function getHeaders() {
		$statusHeader = sprintf('HTTP/1.1 %s %s', $this->statusCode, $this->statusMessage);
		$returnHeaders = array($statusHeader);
		foreach($this->headers as $name => $values) {
			foreach($values as $value) {
				$returnHeaders [] = sprintf('%s: %s', $name, $value);
			}
		}
		return $returnHeaders;
	}

	/**
	 * Returns HTTP header with specified name.
	 *
	 * @param string $name HTTP header name
	 * @param string $default the default value
	 * @return string or null if header name not found
	 */
	public function getHeader($name, $default = null) {
		$name = $this->normalizeHeaderName($name);
		return isset($this->headers [$name]) ? $this->headers [$name] : $default;
	}

	/**
	 * Returns cookies from the current web response.
	 *
	 * @return array Cookies
	 */
	public function getCookies() {
		return $this->cookies;
	}

	/**
	 * Returns cookie with specified name.
	 *
	 * @return array Cookie
	 */
	public function getCookie($name) {
		return isset($this->cookies [$name]) ? $this->cookies [$name] : array();
	}

	/**
	 * Sets content type.
	 * <p>
	 * More details, reference @link http://www.faqs.org/rfcs/rfc2616
	 * 
	 * @param string $contentType the content type to set
	 */
	public function setContentType($contentType) {
		$contentType = $this->normalizeContentType($contentType);
		$this->setHeader('Content-Type', $contentType);
	}

	/**
	 * Sets the character encoding (MIME charset) of the response
	 * being sent to the client, for example, to UTF-8.
	 * If the character encoding has already been set by
	 * {@link #setContentType} this method overrides it.
	 *
	 * @param string $charset
	 */
	public function setCharacterEncoding($charset) {
		$this->options ['charset'] = $charset;
	}

	/**
	 * Returns the name of the character encoding (MIME charset)
	 * used for the body sent in this response.
	 * The character encoding may have been specified explicitly
	 * using the @see WebResponse::setCharacterEncoding() or
	 * @see WebResponse::setContentType() methods
	 *
	 * @return string
	 */
	public function getCharacterEncoding() {
		return isset($this->options ['charset']) ? $this->options ['charset'] : null;
	}

	/**
	 *Set cookies.
	 *
	 * @param  string  $name the HTTP header name
	 * @param  string  $value the value for the cookie
	 * @param  string  $expire the cookie expiration period
	 * @param  string  $path the path
	 * @param  string  $domain the domain name
	 * @param  boolean $secure If secure
	 * @throws <code>InvalidArgumentException</code> If fails to set the cookie
	 */
	public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false) {
		if ($expire !== null) {
			if (is_numeric($expire)) {
				$expire = (int)$expire;
			} else {
				$expire = strtotime($expire);
				if ($expire === false || $expire == -1) {
					throw new InvalidArgumentException(sprintf('Your expire parameter is not valid: %s', $expire));
				}
			}
		}
		
		$this->cookies [$name] = array(
				'name' => $name, 
				'value' => $value, 
				'expire' => $expire, 
				'path' => $path, 
				'domain' => $domain, 
				'secure' => $secure ? true : false);
	}

	/**
	 * Returns content type.
	 *
	 * @return string a string is same 'text/html; charset=utf-8'
	 */
	public function getContentType() {
		return $this->getHeader('Content-Type', $this->options ['Content-Type']);
	}

	/**
	 * Returns response status code.
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Returns response status message.
	 * @return string
	 */
	public function getStatusMessage() {
		return $this->statusMessage;
	}

	/**
	 * Retrieves a normalized header.
	 *
	 * @param  string $name  Header name
	 *
	 * @return string Normalized header
	 */
	protected function normalizeHeaderName($name) {
		$filtered = str_replace(array('-', '_'), ' ', $name);
		$filtered = ucwords(strtolower($filtered));
		$filtered = str_replace(' ', '-', $filtered);
		return $filtered;
	}

	/**
	 * Normalizes the content type by adding the charset for text content types.
	 *
	 * @param  string $content  The content type
	 *
	 * @return string The content type with the charset if needed
	 */
	protected function normalizeContentType($contentType) {
		// add charset if needed (only on text content)
		if (false === stripos($contentType, 'charset') && (0 === stripos($contentType, 'text/') || strlen($contentType) -
				 3 === strripos($contentType, 'xml'))) {
					$contentType .= '; charset=' . $this->options ['charset'];
		}
		
		// change the charset for the response
		$matches = array();
		if (preg_match('/charset\s*=\s*(.+)\s*$/', $contentType, $matches)) {
			$this->options ['charset'] = $matches [1];
		}
		
		return $contentType;
	}
}
