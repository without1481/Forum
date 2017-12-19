<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*========================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

/**
 * vB_Library
 *
 * @package vBForum
 * @access public
 */
class vB_Library
{
	use vB_Trait_NoSerialize;

	protected static $instance = array();

	protected function __construct()
	{

	}

	/**
	 * Returns singleton instance of self.
	 *
	 * @return vB_PageCache		- Reference to singleton instance of the cache handler
	 */
	public static function instance($class)
	{
		/*
			Class names are not case sensitive in PHP, but vars & array keys are.
			Make sure that we get a single instance of the requested class regardless of letter case.
		 */
		$class = ucfirst(strtolower($class));
		$className = 'vB_Library_' . $class;
		if (!isset(self::$instance[$className]))
		{
			self::$instance[$className] = new $className();
		}

		return self::$instance[$className];
	}

	public static function getContentInstance($contenttypeid)
	{
		$contentType = vB_Types::instance()->getContentClassFromId($contenttypeid);
		$className = 'Content_' . $contentType['class'];

		return self::instance($className);
	}

	public static function clearCache()
	{
		self::$instance = array();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 90176 $
|| #######################################################################
\*=========================================================================*/
