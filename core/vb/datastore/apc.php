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
* Class for fetching and initializing the vBulletin datastore from APC
*
* @package	vBulletin
* @version	$Revision: 93906 $
* @date		$Date: 2017-05-02 13:22:17 -0700 (Tue, 02 May 2017) $
*/
class vB_Datastore_APC extends vB_Datastore
{
	/**
	* Indicates if the result of a call to the register function should store the value in memory
	*
	* @var	boolean
	*/
	protected $store_result = false;

	public function resetCache()
	{
		apc_clear_cache('user');
	}

	/**
	* Fetches the contents of the datastore from APC
	*
	* @param	array	Array of items to fetch from the datastore
	*
	* @return	void
	*/
	public function fetch($items)
	{
		if (!function_exists('apc_fetch'))
		{
			trigger_error('APC not installed', E_USER_ERROR);
		}

		if (!sizeof($items = $this->prepare_itemarray($items)))
		{
			return;
		}

		$unfetched_items = array();
		foreach ($items AS $item)
		{
			$this->do_fetch($item, $unfetched_items);
		}

		$this->store_result = true;

		// some of the items we are looking for were not found, lets get them in one go
		if (!empty($unfetched_items))
		{
			if (!($result = $this->do_db_fetch($this->prepare_itemlist($unfetched_items))))
			{
				return false;
			}
		}

		$this->store_result = false;

		$this->check_options();
		return true;
	}

	/**
	* Fetches the data from shared memory and detects errors
	*
	* @param	string	title of the datastore item
	* @param	array	A reference to an array of items that failed and need to fetched from the database
	*
	* @return	boolean
	*/
	protected function do_fetch($title, &$unfetched_items)
	{
		$ptitle = $this->prefix . $title;

		if (($data = apc_fetch($ptitle)) === false)
		{ // appears its not there, lets grab the data, lock the shared memory and put it in
			$unfetched_items[] = $title;
			return false;
		}
		$this->register($title, $data);
		return true;
	}

	/**
	* Sorts the data returned from the cache and places it into appropriate places
	*
	* @param	string	The name of the data item to be processed
	* @param	mixed	The data associated with the title
	*
	* @return	void
	*/
	protected function register($title, $data, $unserialize_detect = 2)
	{
		if ($this->store_result === true)
		{
			$this->storeAPC($title, $data);
		}
		parent::register($title, $data, $unserialize_detect);
	}

	/**
	* Updates the appropriate cache file
	*
	* @param	string	title of the datastore item
	* @param	mixed	The data associated with the title
	*
	* @return	void
	*/
	public function build($title = '', $data = '', $unserialize = 0, $storelocal = true)
	{
		parent::build($title, $data, $unserialize, $storelocal);
		$this->storeAPC($title, $data);
	}

	public function delete($title)
	{
		parent::delete($title);
		$ptitle = $this->prefix . $title;
		apc_delete($ptitle);
	}

	protected function storeAPC($title, $data)
	{
		$ptitle = $this->prefix . $title;

		apc_delete($ptitle);
		apc_store($ptitle, $data);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93906 $
|| #######################################################################
\*=========================================================================*/
