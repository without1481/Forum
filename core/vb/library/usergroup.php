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
 * vB_Library_Usergroup
 *
 * @package vBLibrary
 * @access public
 */
class vB_Library_Usergroup extends vB_Library
{

	/**
	 * Returns a list of all user groups.
	 *
	 * @return	array
	 */
	public function fetchUsergroupList()
	{
		$usergroups = vB::getDatastore()->getValue('usergroupcache');

		//its not clear if the sort logic is needed since the datastore value is
		//*usually* sorted by title already
		$nameList = array();
		foreach ($usergroups AS $key => $group)
		{
			$nameList[$group['title']] = $group;
		}
		uksort($nameList, 'strcasecmp');

		return array_values($nameList);
	}

	public function fetchUsergroupByID($usergroupid)
	{
		$usergroups = vB::getDatastore()->getValue('usergroupcache');
		if (isset($usergroups[$usergroupid]))
		{
			return $usergroups[$usergroupid];
		}
		else
		{
			throw new vb_Exception_Api('invalidid', array('usergroupid'));
		}
	}


	/**
	 *  Returns all of the user groups with ismoderator set
	 *
	 *  @return array usergroupids for each usergroup
	 */
	public function getSuperModGroups()
	{
		$datastore = vB::getDatastore();
		$groups = $datastore->getValue('usergroupcache');
		$bf_ugp_adminpermissions = $datastore->getValue('bf_ugp_adminpermissions');
		$perm = $bf_ugp_adminpermissions['ismoderator'];

		$smod_groups = array();
		foreach($groups as $ugid=> $groupinfo)
		{
			if ($groupinfo['adminpermissions'] & $perm)
			{
				// super mod group
				$smod_groups[] = $ugid;
			}
		}

		return $smod_groups;
	}

	/**
	 * Rebuilds the usergroup datastore cache from the database
	 */
	public function buildDatastore()
	{
		//needed for get_disabled_perms
		require_once(DIR . '/includes/adminfunctions.php');

		$db = vB::getDbAssertor();
		$cache = array();

		$usergroups = $db->select('usergroup', array(), 'title');
		foreach ($usergroups as $usergroup)
		{
			foreach ($usergroup AS $key => $val)
			{
				if (is_numeric($val))
				{
					$usergroup["$key"] += 0;
				}
			}
			$cache["$usergroup[usergroupid]"] = get_disabled_perms($usergroup) + $usergroup;
		}

		vB::getDatastore()->build('usergroupcache', serialize($cache), 1);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93501 $
|| #######################################################################
\*=========================================================================*/
