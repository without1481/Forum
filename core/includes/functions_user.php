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
 * Fetches the URL for a User's Avatar
 *
 * @param	integer	The User ID
 * @param	boolean	Whether to get the Thumbnailed avatar or not
 *
 * @return	array	Information regarding the avatar
 *
 */
function fetch_avatar_url($userid, $thumb = false)
{
	global $vbulletin, $show;
	static $avatar_cache = array();

	if (isset($avatar_cache["$userid"]))
	{
		$avatarurl = $avatar_cache["$userid"]['avatarurl'];
		$avatarinfo = $avatar_cache["$userid"]['avatarinfo'];
	}
	else
	{
		if ($avatarinfo = fetch_userinfo($userid, 2, 0, 1))
		{
			$perms = cache_permissions($avatarinfo, false);
			$avatarurl = array();

			if ($avatarinfo['hascustomavatar'])
			{
				$avatarurl = array('hascustom' => 1);

				if ($vbulletin->options['usefileavatar'])
				{
					$avatarurl[] = $vbulletin->options['avatarurl'] . ($thumb ? '/thumbs' : '') . "/avatar{$userid}_{$avatarinfo['avatarrevision']}.gif";
				}
				else
				{
					$avatarurl[] = "image.php?" . vB::getCurrentSession()->get('sessionurl') . "u=$userid&amp;dateline=$avatarinfo[avatardateline]" . ($thumb ? '&amp;type=thumb' : '') ;
				}

				if ($thumb)
				{
					if ($avatarinfo['width_thumb'] AND $avatarinfo['height_thumb'])
					{
						$avatarurl[] = " width=\"$avatarinfo[width_thumb]\" height=\"$avatarinfo[height_thumb]\" ";
					}
				}
				else
				{
					if ($avatarinfo['avwidth'] AND $avatarinfo['avheight'])
					{
						$avatarurl[] = " width=\"$avatarinfo[avwidth]\" height=\"$avatarinfo[avheight]\" ";
					}
				}
			}
			elseif (!empty($avatarinfo['avatarpath']))
			{
				$avatarurl = array('hascustom' => 0, $avatarinfo['avatarpath']);
			}
			else
			{
				$avatarurl = '';
			}

		}
		else
		{
			$avatarurl = '';
		}

		$avatar_cache["$userid"]['avatarurl'] = $avatarurl;
		$avatar_cache["$userid"]['avatarinfo'] = $avatarinfo;
	}

	if ( // no avatar defined for this user
		empty($avatarurl)
		OR // visitor doesn't want to see avatars
		($vbulletin->userinfo['userid'] > 0 AND !$vbulletin->userinfo['showavatars'])
		OR // user has a custom avatar but no permission to display it
		(!$avatarinfo['avatarid'] AND !($perms['genericpermissions'] & $vbulletin->bf_ugp_genericpermissions['canuseavatar']) AND !$avatarinfo['adminavatar']) //
	)
	{
		$show['avatar'] = false;
	}
	else
	{
		$show['avatar'] = true;
	}

	return $avatarurl;
}

/**
 * (Re)Generates an Activation ID for a user
 *
 * @param	integer	User's ID
 * @param	integer	The group to move the user to when they are activated
 * @param	integer	0 for Normal Activation, 1 for Forgotten Password
 * @param	boolean	Whether this is an email change or not
 *
 * @return	string	The Activation ID
 *
 */
function build_user_activation_id($userid, $usergroupid, $type, $emailchange = 0)
{
	if ($usergroupid == 3 OR $usergroupid == 0)
	{ // stop them getting stuck in email confirmation group forever :)
		$usergroupid = 2;
	}

	/*
		preserve lockout
	 */
	if (!empty($type)) // Forgotten password
	{
		$existing = vB::getDbAssertor()->getRow('useractivation', array(
			'userid' => $userid,
			'type' => $type,
		));
		if (!empty($existing) AND !empty($existing['reset_locked_since']))
		{
			// If we're currently locked, throw an exception and force agent to
			// wait until lockout is over. Note that if the lockout is over,
			// the 'user_replaceuseractivation' query will reset the lockout.
			vB_Library::instance('user')->checkPasswordResetLock($existing);
		}
	}


	vB::getDbAssertor()->assertQuery('useractivation', array(
		vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_DELETE,
		'userid' => $userid,
		'type' => $type,
	));

	$activateid = fetch_random_string(40);
	/*insert query*/
	vB::getDbAssertor()->assertQuery('user_replaceuseractivation', array(
		'userid' => $userid,
		'timenow' => vB::getRequest()->getTimeNow(),
		'activateid' => $activateid,
		'type' => $type,
		'usergroupid' => $usergroupid,
		'emailchange' => intval($emailchange),
	));

	if ($userinfo = vB_User::fetchUserinfo($userid))
	{
		$userdata = new vB_Datamanager_User(vB_DataManager_Constants::ERRTYPE_SILENT);
		$userdata->set_existing($userinfo);
		$userdata->set_bitfield('options', 'noactivationmails', 0);
		$userdata->save();
	}

	return $activateid;
}


/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93969 $
|| #######################################################################
\*=========================================================================*/
