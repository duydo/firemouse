<?php
/**
 * @(#)HttpRequest.php Mar 6, 2009 11:02:09 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

ClassLoader::import('firemouse::core::request::Request');

/**
 * HttpRequest class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage request
 * @version $Id$
 */

class HttpRequest extends Request {
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_POST = 'POST';
	const REQUEST_METHOD_PUT = 'PUT';
	const REQUEST_METHOD_DELETE = 'DELETE';
	const REQUEST_METHOD_HEAD = 'HEAD';
	const SCHEME_HTTP = 'http';
	const SCHEME_HTTPS = 'https';

	public function __construct() {
		$this->initialize();
	}

	public function initialize() {
		parent::initialize();
		//		$this->parameters->add($_GET);
	//		$this->parameters->add($_POST);
	}

	public function getServer($name) {
		return isset($_SERVER [$name]) ? $_SERVER [$name] : NULL;
	}

	public function getContentLength() {}

	public function getCharacterEncoding() {}

	public function getContentType() {}

	public function getProtocol() {
		return $this->getServer('SERVER_PROTOCOL');
	}

	public function getRemoteAddr() {
		return $this->getServer('REMOTE_ADDR');
	}

	public function getRemoteHost() {
		return $this->getServer('HTTP_HOST');
	}

	public function getScheme() {
		return $this->isSecure() ? self::SCHEME_HTTPS : self::SCHEME_HTTP;
	}

	public function getServerName() {
		return $this->getServer('SERVER_NAME');
	}

	public function getServerPort() {
		return $this->getServer('SERVER_PORT');
	}

	public function isSecure() {
		return $this->getServer('HTTPS') == 'on' || $this->getServer('HTTP_SSL_HTTPS') == 'on' || $this->getServer('HTTP_X_FORWARDED_PROTO') ==
				 self::SCHEME_HTTPS;
	}

	public function setCharacterEncoding($env) {}

	/**
	 * Return the value of the given HTTP header. Pass the header name as the
	 * plain, HTTP-specified header name. Ex.: Ask for 'Accept' to get the
	 * Accept header, 'Accept-Encoding' to get the Accept-Encoding header.
	 *
	 * @param String $name HTTP header name
	 */
	public function getHeader($name) {
		// Try to get it from the $_SERVER array first
		$temp = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
		if (!empty($_SERVER [$temp])) {
			return $_SERVER [$temp];
		}
		
		// This seems to be the only way to get the Authorization header on
		// Apache
		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
			if (!empty($headers [$name])) {
				return $headers [$name];
			}
		}
		return NULL;
	}

	public function setHeader($name, $value) {}

	public function setCookie($name, $value) {
		$_COOKIE [$name] = $value;
	}

	public function getCookie($name) {
		if (array_key_exists($name, $_COOKIE)) {
			return $_COOKIE [$name];
		}
		return NULL;
	}

	public function getCookies() {
		return $_COOKIE;
	}

	public function getMethod() {
		return $this->getServer('REQUEST_METHOD');
	}

	public function isPost() {
		return $this->getMethod() == self::REQUEST_METHOD_POST;
	}

	public function isGet() {
		return $this->getMethod() == self::REQUEST_METHOD_GET;
	}

	/**
	 * Returns true if the request is a XMLHttpRequest.
	 *
	 * It works if your JavaScript library set an X-Requested-With HTTP header.
	 * Works with Prototype, Mootools, jQuery, and perhaps others.
	 *
	 * @return bool true if the request is an XMLHttpRequest, false otherwise
	 */
	public function isXMLHttpRequest() {
		return $this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest';
	}

	public function getScriptName() {
		return $this->getServer('SCRIPT_NAME');
	}

	public function getReferer() {
		return $this->getServer('HTTP_REFERER');
	}

	public function getPathInfo() {}

	public function getPathTranslated() {}

	public function getQueryString() {}

	public function getRequestURI() {}

	public function getRequestURL() {}

	public function getSession() {}

	public function getSessionId() {}

	public function getRemoteUser() {

	}

	public function getRawBody() {
		$body = file_get_contents('php://input');
		if (strlen(trim($body)) > 0) {
			return $body;
		}
		return false;
	}
}
