<?php
/**
 * @(#)ClassLoader.class.php Mar 15, 2009 9:41:22 AM
 * Copyright (C) 2009 Duy Do. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package firemouse
 */

/**
 * This utility class is used for loading class files
 *
 * @author Duy Do <doquocduy@gmail.com>
 * @package firemouse
 * @version $Id$
 */

class ClassLoader {
	const PHP_FILE_EXTENSION = '.php';
	const PHP_CLASS_EXTENSION = '.class.php';
	const PACKAGE_SEPARATOR = '::';

	/**
	 * Add include path for {@see ClassLoader::import()} searching classes to import.
	 *
	 * @param string/array $classPath the include path to add
	 */
	public static function addIncludePath($includPath = '.') {
		$includePaths = $includPath;
		if (is_string($includPath) && strlen($includPath) != 0) {
			$includePaths = array($includPath);
		}
		if (!is_array($includePaths)) {
			return;
		}
		foreach($includePaths as $entry) {
			$systemClassPath = get_include_path();
			if (strpos($systemClassPath, $entry) === false) {
				ini_set('include_path', $entry . PATH_SEPARATOR . $systemClassPath);
			}
		}
	}

	/**
	 * Fast add include path. 
	 * This method does not validate $classPath paramater,
	 * also this classpath existed in includepath or not.
	 * @see ClassLoader::addIncludePath()
	 * @param string/array $classPath the classpath to add
	 */
	public static function fastAddIncludePath($includePath = '.') {
		if (is_string($includePath)) {
			ini_set('include_path', $includePath . PATH_SEPARATOR . get_include_path());
		} else {
			foreach($includePath as $entry) {
				ini_set('include_path', $entry . PATH_SEPARATOR . get_include_path());
			}
		}
	}

	/**
	 * Import the class.
	 * <p>
	 * Usage: <br/>
	 * <code>
	 * // Import the class ABC in package xyz
	 * ClassLoader::import('xyz::ABC');
	 * // Import all classes in package xyz
	 * ClassLoader::import('xyz::*');
	 * </code>
	 *
	 * @param string $class the qualified class name.
	 */
	public static function import($class) {
		if (!is_string($class) || strlen(trim($class)) == 0) {
			throw new InvalidArgumentException(sprintf('Invalid class name: %s', $class));
		}
		$path = $class;
		// Check if has star mark
		$starPos = strpos($path, '*');
		if ($starPos !== false) { // has star mark
			$path = substr($path, 0, strlen($path) - (strlen(self::PACKAGE_SEPARATOR) + 1));
			self::importPackage($path);
		} else {
			if (false !== strpos($path, self::PACKAGE_SEPARATOR)) {
				$path = str_replace(self::PACKAGE_SEPARATOR, DIRECTORY_SEPARATOR, $path);
			}
			$classFile = $path . self::PHP_CLASS_EXTENSION;
			require_once ($classFile);
		}
	}

	/**
	 * Import all classes contained in a specified package.
	 * <p>
	 * Usage:
	 * <code>
	 * ClassLoader::importPackage('firemouse::core::io');
	 * </code>
	 * @param string $package the package name
	 */
	public static function importPackage($package = '') {
		$existed = false;
		$path = $package;
		if (false !== strpos($path, self::PACKAGE_SEPARATOR)) {
			$path = str_replace(self::PACKAGE_SEPARATOR, DIRECTORY_SEPARATOR, $path);
		}
		if (file_exists($path)) {
			$existed = true;
		} else {
			$classPaths = explode(PATH_SEPARATOR, get_include_path());
			foreach($classPaths as $classPath) {
				$tmpPath = $classPath . DIRECTORY_SEPARATOR . $path;
				if (file_exists($tmpPath)) {
					$path = $tmpPath;
					$existed = true;
					break;
				}
			}
		}
		if ($existed) {
			$dirHandle = @opendir($path);
			if (false === $dirHandle) {
				return;
			}
			while(($file = readdir($dirHandle)) !== false) {
				$filePath = $path . DIRECTORY_SEPARATOR . $file;
				if (is_file($filePath) && (false !== strpos($filePath, '.class.php') || false !== strpos($filePath, '.php'))) {
					require_once $filePath;
				}
			}
			@closedir($dirHandle);
		}
	}
}
