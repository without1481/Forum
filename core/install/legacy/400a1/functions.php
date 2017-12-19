<?php
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
 * Gets the relationship of one user to another.
 *
 * The relationship level can be:
 *
 * 	3 - User 2 is a Friend of User 1 or is a Moderator
 *  2 - User 2 is on User 1's contact list
 *  1 - User 2 is a registered forum member
 *  0 - User 2 is a guest or ignored user
 *
 * @param int	$user1						- Id of user 1
 * @param int	$user2						- Id of user 2
 */
function fetch_user_relationship($user1, $user2)
{
	global $vbulletin;
	static $privacy_cache = array();

	$user1 = intval($user1);
	$user2 = intval($user2);

	if (!$user2)
	{
		return 0;
	}

	if (isset($privacy_cache["$user1-$user2"]))
	{
		return $privacy_cache["$user1-$user2"];
	}

	if ($user1 == $user2 OR can_moderate(0, '', $user2))
	{
		$privacy_cache["$user1-$user2"] = 3;
		return 3;
	}

	$contacts = vB::getDbAssertor()->assertQuery('userlist', array('userid' => $user1, 'relationid' => $user2));


	$return_value = 1;
	foreach ($contacts as $contact)
	{
		if ($contact['friend'] == 'yes')
		{
			$return_value = 3;
			break;
		}
		else if ($contact['type'] == 'ignore')
		{
			$return_value = 0;
			break;
		}
		else if ($contact['type'] == 'buddy')
		{
			// no break here, we neeed to make sure there is no other more definitive record
			$return_value = 2;
		}
	}


	$privacy_cache["$user1-$user2"] = $return_value;
	return $return_value;
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
