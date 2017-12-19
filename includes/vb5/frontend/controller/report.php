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

class vB5_Frontend_Controller_Report extends vB5_Frontend_Controller
{
	function actionReport()
	{
		// require a POST request for this action
		$this->verifyPostRequest();
		$input = array(
			'reason' => (isset($_POST['reason']) ? trim(strval($_POST['reason'])) : ''),
			'reportnodeid' => (isset($_POST['reportnodeid']) ? trim(intval($_POST['reportnodeid'])) : 0),
		);

		$api = Api_InterfaceAbstract::instance();

		// get user info for the currently logged in user
		$user  = $api->callApi('user', 'fetchCurrentUserinfo', array());

		$reportData = array(
			'rawtext' => $input['reason'],
			'reportnodeid' => $input['reportnodeid'],
			'parentid' => $input['reportnodeid'],
			'userid' => $user['userid'],
			'authorname' => $user['username'],
			'created' => time(),
		);

		$nodeId = $api->callApi('content_report', 'add', array($reportData));
		$this->sendAsJson($nodeId);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93310 $
|| #######################################################################
\*=========================================================================*/
