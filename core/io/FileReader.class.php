<?php
/**
 * @(#)FileReader.class.php Mar 16, 2009 1:14:27 PM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Convenience class for reading character files.
 * <p>
 * <code>FileReader</code> is meant for reading streams of characters.
 * <p>
 * This class supports a only static method FileReader::readURL($url) to fetch content from URL.
 * To play with web content with more options you can use {@link WebIo} - the class that uses
 * either cURL or fsockopen to harvest resources from web. This can be used with scripts that need 
 * a way to communicate with various APIs who support REST.
 * 
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage io
 * @version $Id$
 */

class FileReader {
	const MODE_READ = 'rb';
	private $handle = false;
	private $path;

	/**
	 * Constructors.
	 * 
	 * @param string/File $file the parameter must be a string (local path, url) or instance of {@link File}
	 * @param string $mode the write mode
	 * @throws InvalidArgumentException if $file is null
	 */
	public function __construct($file) {
		$this->open($file);
	}

	/**
	 * Open and lock opened file.
	 *
	 * @param string/File $file
	 * @param string $mode the write mode
	 */
	protected function open($file) {
		$instanceOfFile = $file instanceof File;
		if (!is_string($file) && !$instanceOfFile) {
			throw new InvalidArgumentException('The parameter must be a string or instance of File');
		}
		if ($instanceOfFile) {
			$this->path = $file->getPath();
		} else {
			$this->path = $file;
		}
		$this->handle = fopen($this->path, self::MODE_READ);
	}

	/**
	 * Read content of the file.
	 * @return string or null
	 */
	public function read() {
		return self::_read($this->path);
	}

	/**
	 * This is a just convenient method for fetch content from url using file_get_contents method.
	 * <p>
	 * To play with web content with more options you can use {@link WebIo} - the class that uses
	 * either cURL or fsockopen to harvest resources from web. This can be used with scripts that need 
	 * a way to communicate with various APIs who support REST.
	 * @return string or null
	 */
	public static function readURL($url) {
		return self::_read($url);
	}

	/**
	 * Read content from a path.
	 * @return string or null
	 */
	private static function _read($path) {
		if (!is_string($path)) {
			return null;
		}
		$content = file_get_contents($path);
		if (false !== $content) {
			return $content;
		}
		return null;
	}

	/**
	 * Reads a single character.
	 *
	 * @return The character read, or -1 if the end of the stream has been
	 *         reached
	 *
	 * @exception  IOException  If an I/O error occurs
	 */
	public function readChar() {
		$this->checkHandle();
		$char = fgetc($this->handle);
		if (false !== $char) {
			return $char;
		}
		return -1;
	}

	/**
	 * Reads characters into a portion of an array.
	 *
	 * @param      cbuf     Destination buffer
	 * @param      offset   Offset at which to start storing characters
	 * @param      length   Maximum number of characters to readChar
	 *
	 * @return     The number of characters readChar, or -1 if the end of the 
	 *             stream has been reached
	 *
	 * @throws  IOException  If an I/O error occurs
	 */
	public function readTo(&$cbuf, $offset = 0, $length = null) {
		$this->checkHandle();
		$charNums = 0;
		$length = (int)$length;
		if ($length == 0) {
			$length = filesize($this->path);
		}
		$char = fgetc($this->handle);
		while(false !== $char && $charNums <= $length) {
			$cbuf [$offset++] = $char;
			$charNums++;
			$char = fgetc($this->handle);
		}
		return $charNums;
	}

	/**
	 * Reads a line of text.  A line is considered to be terminated by any one
	 * of a line feed ('\n'), a carriage return ('\r'), or a carriage return
	 * followed immediately by a linefeed.
	 * @return string a string containing the contents of the line, not including
	 *             any line-termination characters, or null if the end of the
	 *             stream has been reached
	 * @throws  IOException  If an I/O error occurs
	 */
	
	public function readLine() {
		$this->checkHandle();
		$line = null;
		$char = fgetc($this->handle);
		while(false !== $char) {
			if ($char == "\r" || $char == "\n") {
				$char = fgetc($this->handle);
				break;
			}
			$line .= $char;
			$char = fgetc($this->handle);
		}
		//		$line = fscanf($this->handle, "%s\r\n");
		return $line;
	}

	/**
	 * Close this stream.
	 *
	 * @return boolean
	 */
	public function close() {
		$this->checkHandle();
		if (fclose($this->handle)) {
			$this->handle = false;
		}
	}

	/**
	 * Resets the file position indicator for handle to the beginning of the file stream. 
	 *  
	 * @return boolean a boolean true on success, false on failure
	 */
	public function reset() {
		$this->checkHandle();
		return rewind($this->handle);
	}

	/**
	 * Ensure handle is open.
	 */
	protected function checkHandle() {
		if ($this->handle !== false) {
			require_once 'firemouse/core/exception/IOException.class.php';
			throw new IOException('Stream closed');
		}
	}
}
