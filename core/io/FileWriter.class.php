<?php
/**
 * @(#)FileWriter.class.php Mar 16, 2009 9:01:47 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * Convenience class for writing character files.
 * <p>
 * <code>FileWriter</code> is meant for writing streams of characters.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @subpackage io
 * @version $Id$
 */

class FileWriter {
	const MODE_READ_WRITE = 'r+b';
	/**
	 * Truncates existing file data, use with care
	 */
	const MODE_WRITE_CREATE_DESTRUCTIVE = 'wb';
	/**
	 * Truncates existing file data, use with care
	 */
	const MODE_READ_WRITE_CREATE_DESTRUCTIVE = 'w+b';
	const MODE_WRITE_CREATE = 'ab';
	const MODE_READ_WRITE_CREATE = 'a+b';
	const MODE_WRITE_CREATE_STRICT = 'xb';
	const MODE_READ_WRITE_CREATE_STRICT = 'x+b';
	
	private $mode = self::MODE_WRITE_CREATE;
	private $handle;
	private $path;

	/**
	 * Constructors.
	 * 
	 * @param string/File $file
	 * @throws InvalidArgumentException if $file is null
	 */
	public function __construct($file) {
		$this->openAndLock($file);
	}

	/**
	 * Set write mode.
	 *
	 * @param int $mode the mode to set
	 */
	public function setWriteMode($mode) {
		if (!is_string($mode) || strlen($mode) == 0) {
			throw new InvalidArgumentException(sprintf('Write mode must be a non-empty string: "%s"', $mode));
		}
		$this->mode = $mode;
	}

	/**
	 * Open and lock opened file.
	 *
	 * @param string/File $file
	 * @param string $mode the write mode
	 * @throws InvalidArgumentException if $file is null
	 */
	protected function openAndLock($file = null) {
		if (is_null($file)) {
			throw new InvalidArgumentException('$file is null');
		}
		if ($file instanceof File) {
			$this->path = $file->getPath();
		} else {
			$this->path = $file;
		}
		$this->handle = fopen($this->path, $this->mode);
		if ($this->handle !== false) {
			flock($this->handle, LOCK_EX);
		}
	}

	/**
	 * Write content to the file.
	 *
	 * @param string $content
	 * @return int number of bytes to write
	 */
	public function write($content) {
		$this->checkHandle();
		return fwrite($this->handle, $content);
	}

	/**
	 * Write content with newline.
	 *
	 * @param string $content
	 * @return int number of bytes to write
	 */
	public function writeln($content) {
		return $this->write($content . "\n");
	}

	/**
	 * Flushes the output buffer.
	 *
	 * @return boolean true on success or false on failure
	 */
	public function flush() {
		$this->checkHandle();
		return fflush($this->handle);
	}

	/**
	 * Unlock file and close this stream writter.
	 *
	 * @return boolean true of false
	 */
	public function close() {
		$this->checkHandle();
		flock($this->handle, LOCK_UN);
		return fclose($this->handle);
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
