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

/**#@+
* The maximum sizes for the "small" social group icons
*/
define('FIXED_SIZE_GROUP_ICON_WIDTH', 200);
define('FIXED_SIZE_GROUP_ICON_HEIGHT', 200);
define('FIXED_SIZE_GROUP_THUMB_WIDTH', 80);
define('FIXED_SIZE_GROUP_THUMB_HEIGHT', 80);
/**#@-*/



/**
 * Takes information regardign a group, and prepares the information within it
 * for display
 *
 * @param	array	Group Array
 *
 * @return	array	Group Array with prepared information
 * @deprecated  This is only used in admincp/socialgroups.php and will be removed
 * 	once that usage is gone.  In the meantime expect parts not used by the caller
 * 	to be removed if they prove problematic.
 */
function prepare_socialgroup($group)
{
	global $vbulletin;

	if (!is_array($group))
	{
		return array();
	}

	$group['joindate'] = (!empty($group['joindate']) ?
		vbdate($vbulletin->options['dateformat'], $group['joindate'], true) : '');
	$group['createtime'] = (!empty($group['createdate']) ?
		vbdate($vbulletin->options['timeformat'], $group['createdate'], true) : '');
	$group['createdate'] = (!empty($group['createdate']) ?
		vbdate($vbulletin->options['dateformat'], $group['createdate'], true) : '');

	$group['lastupdatetime'] = (!empty($group['lastupdate']) ?
		vbdate($vbulletin->options['timeformat'], $group['lastupdate'], true) : '');
	$group['lastupdatedate'] = (!empty($group['lastupdate']) ?
		vbdate($vbulletin->options['dateformat'], $group['lastupdate'], true) : '');

	$group['visible'] = vb_number_format($group['visible']);
	$group['moderation'] = vb_number_format($group['moderation']);

	$group['members'] = vb_number_format($group['members']);
	$group['moderatedmembers'] = vb_number_format($group['moderatedmembers']);

	$group['categoryname'] = htmlspecialchars_uni($group['categoryname']);
	$group['discussions'] = vb_number_format($group['discussions']);
	$group['lastdiscussion'] = fetch_word_wrapped_string(fetch_censored_text($group['lastdiscussion']));

	if (!($group['options'] & $vbulletin->bf_misc_socialgroupoptions['enable_group_albums']))
	{
		// albums disabled in this group - force 0 pictures
		$group['picturecount'] = 0;
	}
	$group['rawpicturecount'] = $group['picturecount'];
	$group['picturecount'] = vb_number_format($group['picturecount']);

	$group['rawname'] = $group['name'];
	$group['rawdescription'] = $group['description'];

	$group['name'] = fetch_word_wrapped_string(fetch_censored_text($group['name']));

	if ($group['description'])
	{
 		$group['shortdescription'] = fetch_word_wrapped_string(fetch_censored_text(vB_String::fetchTrimmedTitle($group['description'], 185)));
	}
	else
	{
		$group['shortdescription'] = $group['name'];
	}

 	$group['mediumdescription'] = fetch_word_wrapped_string(fetch_censored_text(vB_String::fetchTrimmedTitle($group['description'], 1000)));
	$group['description'] = nl2br(fetch_word_wrapped_string(fetch_censored_text($group['description'])));

	$group['is_owner'] = ($group['creatoruserid'] == $vbulletin->userinfo['userid']);

	$group['is_automoderated'] = (
		$group['options'] & $vbulletin->bf_misc_socialgroupoptions['owner_mod_queue']
		AND $vbulletin->options['sg_allow_owner_mod_queue']
		AND !$vbulletin->options['social_moderation']
	);

	$group['canviewcontent'] = (
		(
			(
				!($group['options'] & $vbulletin->bf_misc_socialgroupoptions['join_to_view'])
				OR !$vbulletin->options['sg_allow_join_to_view']
			) // The above means that you dont have to join to view
			OR $group['membertype'] == 'member'
			// Or can moderate comments
			OR can_moderate(0, 'canmoderategroupmessages')
			OR can_moderate(0, 'canremovegroupmessages')
			OR can_moderate(0, 'candeletegroupmessages')
			OR fetch_socialgroup_perm('canalwayspostmessage')
			OR fetch_socialgroup_perm('canalwascreatediscussion')
		)
	);

 	$group['lastpostdate'] = vbdate($vbulletin->options['dateformat'], $group['lastpost'], true);
 	$group['lastposttime'] = vbdate($vbulletin->options['timeformat'], $group['lastpost']);

 	$group['lastposterid'] = $group['canviewcontent'] ? $group['lastposterid'] : 0;
 	$group['lastposter'] = $group['canviewcontent'] ? $group['lastposter'] : '';

 	// check read marking
	//remove notice and make readtime determination a bit more clear
	if (!empty($group['readtime']))
	{
		$readtime = $group['readtime'];
	}
	else
	{
		$readtime = fetch_bbarray_cookie('group_marking', $group['groupid']);
		if (!$readtime)
		{
			$readtime = $vbulletin->userinfo['lastvisit'];
		}
	}

 	// get thumb url
 	$group['iconurl'] = fetch_socialgroupicon_url($group, true);

 	// check if social group is moderated to join
 	$group['membermoderated'] = ('moderated' == $group['type']);

 	// posts older than markinglimit days won't be highlighted as new
	$oldtime = (TIMENOW - ($vbulletin->options['markinglimit'] * 24 * 60 * 60));
	$readtime = max((int)$readtime, $oldtime);
	$group['readtime'] = $readtime;
	$group['is_read'] = ($readtime >= $group['lastpost']);

	// Legacy Hook 'group_prepareinfo' Removed //

	return $group;
}


/**
 * Checks a single social group permission.
 *
 * @param	string	The permission to check
 *
 * @return	boolean	Whether or not the current user has the permission.
 */
function fetch_socialgroup_perm($perm)
{
	global $vbulletin;

	$userinfo = $vbulletin->userinfo;

	if (isset($vbulletin->bf_ugp_socialgrouppermissions["$perm"]))
	{
		return $userinfo['permissions']['socialgrouppermissions'] &
				$vbulletin->bf_ugp_socialgrouppermissions["$perm"];
	}

	return false;
}


/**
 * Prepares the appropriate url for a group icon.
 * The url is based on whether fileavatars are in use, and whether a thumb is required.
 *
 * @param array mixed $groupinfo				- GroupInfo array of the group to fetch the icon for
 * @param boolean $thumb						- Whether to return a thumb url
 * @param boolean $path							- Whether to fetch the path or the url
 * @param boolean $force_file					- Always get the file path as if it existed
 */
function fetch_socialgroupicon_url($groupinfo, $thumb = false, $path = false, $force_file = false)
{
	global $vbulletin;

	$iconurl = false;

	if ($vbulletin->options['sg_enablesocialgroupicons'])
	{
		if (!$groupinfo['icondateline'])
		{
			return vB_Template_Runtime::fetchStyleVar('unknownsgicon');
		}

		if ($vbulletin->options['usefilegroupicon'] OR $force_file)
		{
			$iconurl = ($path ? $vbulletin->options['groupiconpath'] : $vbulletin->options['groupiconurl']) . ($thumb ? '/thumbs' : '') . '/socialgroupicon' . '_' . $groupinfo['groupid'] . '_' . $groupinfo['icondateline'] . '.gif';
		}
		else
		{
			$iconurl = 'image.php?' . vB::getCurrentSession()->get('sessionurl') . 'groupid=' . $groupinfo['groupid'] . '&amp;dateline=' . $groupinfo['icondateline'] . ($thumb ? '&amp;type=groupthumb' : '');
		}
	}

	return $iconurl;
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 91491 $
|| #######################################################################
\*=========================================================================*/
