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

class vB5_Frontend_Controller_Style extends vB5_Frontend_Controller
{
	public function actionSaveGeneratedStyle()
	{
		// require a POST request for this action
		$this->verifyPostRequest();

		//$scheme, $parentid, $title, $displayorder = 1, $userselect = false
		//, $_POST['type'], $_POST['id']

		$response = Api_InterfaceAbstract::instance()->callApi('style', 'generateStyle', array(
				'scheme' => $_POST['scheme'],
				'type' => $_POST['type'],
				'parentid' => $_POST['parentid'],
				'title' => $_POST['name'],
				'displayorder' => empty($_POST['displayorder'])?1:$_POST['displayorder'],
				'userselect' => !empty($_POST['userselect'])));

		$this->sendAsJson($response);
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 85455 $
|| #######################################################################
\*=========================================================================*/
