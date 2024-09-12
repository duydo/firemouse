<?php
/**
 * @(#)File.class.php Mar 15, 2009 11:11:27 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * File class.
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package 
 * @subpackage io
 * @version $Id$
 */

class File {
	const FILE_SEPARATOR = DIRECTORY_SEPARATOR;
	//For listing directories
	const TYPE_BOTH = 0;
	const TYPE_DIR = 1;
	const TYPE_FILE = 2;
	
	// For set permission
	const ACCESS_READ = 04;
	const ACCESS_WRITE = 02;
	const ACCESS_EXECUTE = 01;
	
	private $path;

	public function __construct($fileName = null) {
		if (!is_string($fileName) || strlen($fileName) == 0) {
			throw new InvalidArgumentException('$fileName is empty');
		}
		$this->path = $fileName;
	}

	/**
	 * Tests whether the file denoted by this abstract pathname is a normal
	 * file.  A file is <em>normal</em> if it is not a directory and, in
	 * addition, satisfies other system-dependent criteria.
	 *
	 * @return boolean a boolean true if this file is a regular file; otherwise false
	 */
	public function isFile() {
		return is_file($this->path);
	}

	/**
	 * Tests whether the file denoted by this abstract pathname is a
	 * directory.
	 *
	 * @return boolean a boolean true if this file is a directory; otherwise false
	 */
	public function isDir() {
		return is_dir($this->path);
	}

	/**
	 * Tests whether the file denoted by this abstract pathname is symbolic link.
	 *
	 * @return boolean a boolean true if this file is a symbolic link; otherwise false
	 */
	public function isLink() {
		return is_link($this->path);
	}

	/**
	 * Tests whether this abstract pathname is absolute.  The definition of
	 * absolute pathname is system dependent.  On UNIX systems, a pathname is
	 * absolute if its prefix is <code>"/"</code>.  On Microsoft Windows systems, a
	 * pathname is absolute if its prefix is a drive specifier followed by
	 * <code>"\\"</code>, or if its prefix is <code>"\\\\"</code>.
	 *
	 */
	public function isAbsolute() {
		$absolutePathOnWins = '/(([A-Za-z])+:\\\)(.*)$/';
		$absolutePathOnLinux = '/([\\/])(.*)$/';
		return preg_match($absolutePathOnLinux, $this->path) || preg_match($absolutePathOnWins, $this->path);
	}

	/**
	 * Returns file's name.
	 *
	 * @return string
	 */
	public function getName() {
		return basename($this->path);
	}

	/**
	 * Returns the absolute pathname string of this abstract pathname.
	 *
	 * @return string
	 */
	public function getAbsolutePath() {
		return realpath($this->path);
	}

	/**
	 * Returns path of the file.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Returns the length of the file denoted by this abstract pathname.
	 *
	 * @return int a size in bytes of this file
	 */
	public function length() {
		return filesize($this->path);
	}

	/**
	 * Returns the number of unallocated bytes in the partition named by this abstract path name.
	 *
	 * @return float
	 */
	public function getFreeSpace() {
		return disk_free_space($this->path);
	}

	/**
	 * Returns the size of the partition named by this abstract pathname.
	 *
	 * @return float
	 */
	public function getTotalSpace() {
		return disk_total_space($this->getPath());
	}

	/**
	 * Tests whether the file or directory denoted by this abstract pathname
	 * exists.
	 *
	 * @return boolean a boolean true or false
	 */
	public function exists() {
		return file_exists($this->path);
	}

	/**
	 * Check if this file is readable.
	 *
	 * @return boolean true if can read, false if can not read
	 */
	public function canRead() {
		return is_readable($this->path);
	}

	/**
	 * Check if this file is writable.
	 *
	 * @return boolean true if can write, false if can not write
	 */
	public function canWrite() {
		return is_writable($this->path);
	}

	/**
	 * Check if this file is executable.
	 *
	 * @return boolean true if can execute, false if can not execute
	 */
	public function canExecute() {
		return is_executable($this->path);
	}

	/**
	 * Returns an array of strings naming the files and directories that satisfy the specified filter in the
	 * directory.
	 * <p>
	 * <ul>
	 * <li>0: Directories and files returned</li>
	 * <li>1: Only files returned</li>
	 * <li>2: Only directories returned</li>
	 * </ul>
	 * @param int $type the type of file returned
	 * @param string $filter the regular expression filter
	 * @return array an array of strings naming the files and directories
	 */
	public function listAsString($type = 0, $filter = '/[^.]/') {
		if (!$this->isDir()) {
			return array();
		}
		$handle = @opendir($this->path);
		if (!$handle) {
			return array();
		}
		$files = array();
		while(($file = readdir($handle)) !== false) {
			$path = $this->path . DIRECTORY_SEPARATOR . $file;
			$ok = false;
			switch((int)$type) {
				case self::TYPE_DIR:
					$ok = is_dir($path);
				break;
				case self::TYPE_FILE:
					$ok = is_file($path);
				break;
				default:
					$ok = is_file($path) || is_dir($path);
				break;
			}
			
			if ($ok) {
				if (!empty($filter)) {
					if (preg_match($filter, $file)) {
						$files [] = $file;
					}
				} else {
					$files [] = $file;
				}
			}
		}
		@closedir($handle);
		return $files;
	}

	/**
	 * Return array of {@link File} objects, one for each file or directory 
	 * that satisfy the specified filter in the directory. 
	 * <p>
	 * <ul>
	 * <li>0: Directories and files returned</li>
	 * <li>1: Only files returned</li>
	 * <li>2: Only directories returned</li>
	 * </ul>
	 * @param int $type the type of file returned
	 * @param string $filter the regular expression filter
	 * @return array an array of {@link File}
	 */
	public function listAsFile($type = 0, $filter = '/[^.]/') {
		$files = $this->listAsString($type, $filter);
		$ret = array();
		foreach($files as $file) {
			$ret [] = new File($this->path . DIRECTORY_SEPARATOR . $file);
		}
		return $ret;
	}

	// Modify operations
	

	/**
	 * Atomically creates a new, empty file named by this abstract pathname if
	 * and only if a file with this name does not yet exist.  The check for the
	 * existence of the file and the creation of the file if it does not exist
	 * are a single operation that is atomic with respect to all other
	 * filesystem activities that might affect the file.
	 * @return boolean <code>true</code> if the named file does not exist and was
	 *         successfully created; <code>false</code> if the named file
	 *         already exists
	 */
	public function createNewFile() {
		if ($this->exists()) {
			return false;
		}
		$handle = @fopen($this->path, 'x');
		if (!$handle) {
			return false;
		}
		@fclose($handle);
		return true;
	}

	/**
	 * Create temporary file in specified directory.
	 * <p>
	 * If the directory does not exist, this method may generate a file in the system's temporary directory. 
	 *  
	 *
	 * @param File $directory the directory the temporary file generated in
	 * @param string $prefix the prefix of the temporary file name
	 * @return File a instance of File object, or null if can not create
	 */
	public static function createTempFile($prefix = '', File $directory = null) {
		$path = $directory != null ? $directory->getPath() : null;
		$temp = tempnam($path, $prefix);
		if ($temp === false) {
			return false;
		}
		return new File($temp);
	}

	/**
	 * Delete this file. If the file is a directory, the directory must be empty.
	 * @return boolean <code>true</code> if and only if the file or directory is
	 *         successfully deleted; <code>false</code> otherwise
	 */
	public function delete() {
		if (!$this->exists()) {
			return false;
		}
		if ($this->isDir()) {
			return rmdir($this->path);
		}
		return unlink($this->path);
	}

	/**
	 * Creates the directory named by this abstract pathname.
	 * 
	 * @param int $mode default mode is 0777
	 * @param boolean $recursive default value is false
	 * @return  <code>true</code> if and only if the directory was
	 *          created; <code>false</code> otherwise
	 */
	public function mkdir($mode = 0777, $recursive = false) {
		return @mkdir($this->path, $mode, $recursive);
	}

	/**
	 * Creates the directory named by this abstract pathname, including any
	 * necessary but nonexistent parent directories.  Note that if this
	 * operation fails it may have succeeded in creating some of the necessary
	 * parent directories.
	 * @param int $mode default mode is 0777
	 * @return boolean <code>true</code> if and only if the directory was created,
	 *          along with all necessary parent directories; <code>false</code>
	 *          otherwise
	 */
	public function mkdirs($mode = 0777) {
		return $this->mkdir($mode, true);
	}

	/**
	 * Renames the file denoted by this abstract pathname.
	 *
	 * @param File $dest
	 * @return boolean a boolean true if rename successfully; otherwise false
	 */
	public function renameTo(File $dest) {
		if (!$this->exists()) {
			return false;
		}
		$oldFile = $this->getPath();
		$newFile = $dest->getPath();
		
		if (!@rename($oldFile, $newFile)) {
			if (@copy($oldFile, $newFile)) {
				$this->delete();
				return true;
			}
			return false;
		}
		return true;
	}

	// Security operations
	

	/**
	 * Sets the owner's or everybody's write permission for this abstract
	 * pathname.
	 *
	 * @param boolean $writable
	 * @param boolean $ownerOnly
	 * @return boolean true on success, false on failure
	 */
	public function setWritable($writable = true, $ownerOnly = false) {
		return $this->setPermission(self::ACCESS_WRITE, $writable, $ownerOnly);
	}

	/**
	 * Sets the owner's or everybody's read permission for this abstract
	 * pathname.
	 *
	 * @param boolean $readable
	 * @param boolean $ownerOnly
	 * @return boolean true on success, false on failure
	 */
	public function setReadable($readable = true, $ownerOnly = false) {
		return $this->setPermission(self::ACCESS_READ, $readable, $ownerOnly);
	}

	/**
	 * Sets the owner's or everybody's execute permission for this abstract
	 * pathname.
	 *
	 * @param boolean $executable
	 * @param boolean $ownerOnly
	 * @return boolean true on success, false on failure
	 */
	public function setExecutable($executable = true, $ownerOnly = false) {
		return $this->setPermission(self::ACCESS_EXECUTE, $executable, $ownerOnly);
	}

	//@todo Not yet implement setPermission($accessMode, $enable, $ownerOnly = true)
	protected function setPermission($accessMode, $enable, $ownerOnly = false) {
		$currentPermission = fileperms($this->path) & 0777;
		$ownerMode = $accessMode * 0100;
		$groupMode = $accessMode * 010;
		$globalMode = $accessMode * 01;
		
		$newPermision = $currentPermission;
		
		if ($enable) {
			// Always check & set permission for owner
			if (!($ownerMode & $currentPermission)) {
				$newPermision += $ownerMode;
			}
			
			if ($ownerOnly) {
				if ($groupMode & $currentPermission) {
					$newPermision -= $groupMode;
				}
				
				if ($globalMode & $currentPermission) {
					$newPermision -= $globalMode;
				}
			
			} else {
				if (!($groupMode & $currentPermission)) {
					$newPermision += $groupMode;
				}
				
				if (!($globalMode & $currentPermission)) {
					$newPermision += $globalMode;
				}
			
			}
		
		} else {
			// Always check & set permission for owner
			if ($ownerMode & $currentPermission) {
				$newPermision -= $ownerMode;
			}
			
			if (!$ownerOnly) {
				if ($groupMode & $currentPermission) {
					$newPermision -= $groupMode;
				}
				
				if ($globalMode & $currentPermission) {
					$newPermision -= $globalMode;
				}
			}
		}
		if ($newPermision != $currentPermission) {
			return chmod($this->path, decoct($newPermision));
		}
		return true;
	}
}
