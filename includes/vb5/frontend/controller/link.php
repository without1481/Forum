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

class vB5_Frontend_Controller_Link extends vB5_Frontend_Controller
{
	function actionGetlinkdata()
	{
		// require a POST request for this action
		$this->verifyPostRequest();

		$input = array(
			'url' => trim($_REQUEST['url']),
		);

		$api = Api_InterfaceAbstract::instance();

		$video = $api->callApi('content_video', 'getVideoFromUrl', array($input['url']));
		$data = $api->callApi('content_link', 'parsePage', array($input['url']));

		if ($video AND empty($video['errors']))
		{
			$result = vB5_Template::staticRenderAjax('video_edit', array(
				'video' => $video,
				'existing' => 0,
				'editMode' => 1,
				'title' => $data['title'],
				'url' => $input['url'],
				'meta' => $data['meta'],
			));
		}
		else
		{
			if ($data AND empty($data['errors']))
			{
				$result = vB5_Template::staticRenderAjax('link_edit', array(
					'images' => $data['images'],
					'title' => $data['title'],
					'url' => $input['url'],
					'meta' => $data['meta'],
				));
			}
			else
			{
				$result = array(
					'template' => array('error' => 'upload_invalid_url'),
					'css_links' => array(),
				);
			}
		}

		$this->sendAsJson($result);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93055 $
|| #######################################################################
\*=========================================================================*/
