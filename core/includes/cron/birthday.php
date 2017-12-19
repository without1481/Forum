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

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################

$datastore = vB::getDatastore();
$vboptions = $datastore->getValue('options');

if($vboptions['enablebirthdayemails'])
{
	$bf_misc_useroptions = $datastore->getValue('bf_misc_useroptions');
	$bf_ugp_genericoptions = $datastore->getValue('bf_ugp_genericoptions');
	$usergroupcache = $datastore->getValue('usergroupcache');

	$ids = array();
	foreach($usergroupcache AS $usergroupid => $usergroup)
	{
		if (
			$usergroup['genericoptions'] & $bf_ugp_genericoptions['showbirthday'] AND
			$usergroup['genericoptions'] & $bf_ugp_genericoptions['isnotbannedgroup'] AND
			!in_array($usergroup['usergroupid'], array(1, 3, 4))
		)
		{
			$ids[] = $usergroupid;
		}
	}

	if($ids)
	{
		$now = vB::getRequest()->getTimeNow();
		$today = date('m-d', $now);

		$conditions = array(
			'usergroupid' => $ids,
			array('field' => 'options', 'value' => $bf_misc_useroptions['adminemail'], 'operator' =>  vB_dB_Query::OPERATOR_AND),
			array('field' => 'birthday', 'value' => $today . '-', 'operator' =>  vB_dB_Query::OPERATOR_BEGINS ),
		);

		if ($vboptions['birthdayemaillookback'])
		{
			$cutoff = $now - ($vboptions['birthdayemaillookback'] * 86400);
			$conditions[] = array('field' => 'lastvisit', 'value' => $cutoff . '-', 'operator' => vB_dB_Query::OPERATOR_GT);
		}

		$birthdays = vB::getDbAssertor()->select('user', $conditions, false, array('username', 'email', 'languageid'));

		$usersEmailed = array();
		vB_Mail::vbmailStart();
		foreach ($birthdays AS $userinfo)
		{
			$username = unhtmlspecialchars($userinfo['username']);
			$maildata = vB_Api::instanceInternal('phrase')->fetchEmailPhrases(
				'birthday',
				array(
					$username,
					$vboptions['bbtitle']
				),
				array(
					$vboptions['bbtitle']
				),
				$userinfo['languageid']
			);

			vB_Mail::vbmail($userinfo['email'], $maildata['subject'], $maildata['message']);
			$usersEmailed[] = $userinfo['username'];
		}
		vB_Mail::vbmailEnd();

		if ($usersEmailed)
		{
			log_cron_action(implode(', ', $usersEmailed), $nextitem, 1);
		}
	}
}
/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 94144 $
|| #######################################################################
\*=========================================================================*/
