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

//This removes private messages with no activity for 30 days.

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

//First we get a list up to 500 records.

$assertor = vB::getDbAssertor();
$records = $assertor->assertQuery('vBForum:getDeletedMsgs', array(
	'deleteLimit' => vB::getRequest()->getTimeNow(),
	vB_dB_Query::PARAM_LIMIT => 500
));

if ($records AND $records->valid())
{
	$nodeids = array();
	foreach ($records as $record)
	{
		$nodeids[] = $record['nodeid'];
	}

	vB_Library::instance('content_privatemessage')->delete($nodeids);
}

log_cron_action('', $nextitem, 1);

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 89372 $
|| #######################################################################
\*=========================================================================*/
