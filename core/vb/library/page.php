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
 * vB_Library_Page
 *
 * @package vBLibrary
 * @access public
 */
class vB_Library_Page extends vB_Library
{
	protected $lastCacheData = array();

	// array of info used for precaching
	protected $preCacheInfo = array();

	//Last time we saved cache- useful to prevent thrashing
	protected $lastpreCache = false;

	//Minimum time between precache list updates, in seconds
	const MIN_PRECACHELIFE = 300;


	/**
	 * This preloads information for the current page.
	 *
	 * @param	string	the identifier for this page, which comes from the route class.
	 */
	public function preload($pageKey)
	{
		$this->lastCacheData = vB_Cache::instance(vB_Cache::CACHE_LARGE)->read("vbPre_$pageKey");

		//If we don't have anything, just return;
		if (!$this->lastCacheData)
		{
			return;
		}

		$this->lastpreCache = $this->lastCacheData['cachetime'];

		if (!empty($this->lastCacheData['data']))
		{
			foreach ($this->lastCacheData['data'] AS $class => $tasks)
			{
				try
				{
					$library = vB_Library::instance($class);
					foreach ($tasks AS $method => $params)
					{
						if (method_exists($library, $method))
						{
							$reflection = new ReflectionMethod($library, $method);
							$reflection->invokeArgs($library, $params);
						}
					}

				}
				catch(exception $e)
				{
					//nothing to do. Just try the other methods.
				}
			}
		}
	}

	/**
	 * This saves preload information for the current page.
	 * @param string $pageKey -- the identifier for this page, which comes from the route class.
	 */
	public function savePreCacheInfo($pageKey)
	{
		$timenow = vB::getRequest()->getTimeNow();

		if (empty($this->preCacheInfo) OR
			(($timenow - intval($this->lastpreCache)) < self::MIN_PRECACHELIFE)
		)
		{
			return;
		}
		$data = array('cachetime' => $timenow, 'data' => $this->preCacheInfo);

		vB_Cache::instance(vB_Cache::CACHE_LARGE)->write("vbPre_$pageKey", $data, 300);
	}

	/**
	 * This saves preload information for the current page.
	 *
	 *	@param	string $apiClass -- name of the api class
	 * 	@param	string $method -- name of the api method that should be called
	 *	@param	mixed $params -- array of method parameters that should be passed
	 */
	public function registerPrecacheInfo($apiClass, $method, $params)
	{
		//if we have cached within the last five minutes do nothing.
		if ((vB::getRequest()->getTimeNow() - intval($this->lastpreCache)) < self::MIN_PRECACHELIFE)
		{
			return;
		}

		if (!isset($this->preCacheInfo[$apiClass]))
		{
			$this->preCacheInfo[$apiClass] = array();
		}

		$this->preCacheInfo[$apiClass][$method] = $params;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
