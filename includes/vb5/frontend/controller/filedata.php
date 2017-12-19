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

class vB5_Frontend_Controller_Filedata extends vB5_Frontend_Controller
{

	/**
	 * This methods returns the contents of a specific image
	 */
/*
	public function actionFetch()
	{
		// dev note: if you're wondering why a filedata/fetch url isn't hitting this function, it's probably because
		// it's going through vB5_Frontend_ApplicationLight's fetchImage()
	}
*/

	/**
	 * This is called on a delete- only used by the blueimp slider and doesn't do anything
	 */
	public function actionDelete()
	{
		//Note that we shouldn't actually do anything here. If the filedata record isn't
		//used it will soon be deleted.
		$contents = '';
		header('Content-Type: image/png');
		header('Accept-Ranges: bytes');
		header('Content-transfer-encoding: binary');
		header("Content-Length: " . strlen($contents) );
		header("Content-Disposition: inline; filename=\"1px.png\"");
		header('Cache-control: max-age=31536000, private');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		die($contents);
	}

	/**
	 * gets a gallery and returns in json format for slideshow presentation.
	 */
	public function actionGallery()
	{
		// Don't need to require POST, since this is only displaying content

		//We need a nodeid
		if (!empty($_REQUEST['nodeid']))
		{
			$nodeid = $_REQUEST['nodeid'];
		}
		else if (!empty($_REQUEST['id']))
		{
			$nodeid = $_REQUEST['id'];
		}
		else
		{
			return '';
		}

		//get the raw data.
		$api = Api_InterfaceAbstract::instance();

		$config = vB5_Config::instance();
		$phraseApi = vB5_Template_Phrase::instance();
		$gallery = array('photos' => array());
		switch (intval($nodeid))
		{
			case 0:
			case -1: //All Videos
				throw new vB_Exception_Api('invalid_request');
			case -2: //All non-Album photos and attachments
				if ((empty($_REQUEST['userid']) OR !intval($_REQUEST['userid'])) AND
					(empty($_REQUEST['channelid']) OR !intval($_REQUEST['channelid'])))
				{
					throw new vB_Exception_Api('invalid_request');
				}
				$galleryData = $api->callApi('profile', 'getSlideshow', array(
					array(
						'userid' => isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0,
						'channelid' => isset($_REQUEST['channelid']) ? intval($_REQUEST['channelid']) : 0,
						'dateFilter' => isset($_REQUEST['dateFilter']) ? $_REQUEST['dateFilter'] : '',
						'searchlimit' => isset($_REQUEST['perpage']) ? $_REQUEST['perpage'] : '',
						'startIndex' => isset($_REQUEST['startIndex']) ? $_REQUEST['startIndex'] : ''
					)
				));

				if (empty($galleryData))
				{
					return array();
				}

				foreach($galleryData AS $photo)
				{
					$titleVm = !empty($photo['parenttitle']) ? $photo['parenttitle'] : $photo['startertitle'];
					$route = $photo['routeid'];
					if($photo['parenttitle'] == 'No Title' AND $photo['parentsetfor'] > 0)
					{
						$titleVm = $phraseApi->getPhrase('visitor_message_from_x', array($photo['authorname']));
						$route = 'visitormessage';
					}
					$userLink =  vB5_Template_Options::instance()->get('options.frontendurl') . $api->callApi('route', 'getUrl', array('route' => 'profile',
						'data' => array('userid' => $photo['userid'], 'username' => $photo['authorname']), 'extra' => array()));
					$topicLink = vB5_Template_Options::instance()->get('options.frontendurl') . '/' . $api->callApi('route', 'getUrl', array('route' => $route,
						'data' => array('title' => $titleVm, 'nodeid' => $photo['parentnode']), 'extra' => array()));
					$title = $photo['title'] != null ? $photo['title'] : '';
					$htmltitle = ( ($photo['htmltitle'] != null) ? $photo['htmltitle'] : '' );
					$photoTypeid = vB_Types::instance()->getContentTypeID('vBForum_Photo');
					$attachTypeid = vB_Types::instance()->getContentTypeID('vBForum_Attach');
					if ($photo['contenttypeid'] === $photoTypeid) {
						$queryVar = 'photoid';
					} else if ($photo['contenttypeid'] === $attachTypeid) {
						$queryVar = 'id';
					}
					$gallery['photos'][] = array(
						'title' => $title,
						'htmltitle' => $htmltitle,
						'url' => 'filedata/fetch?' . $queryVar . '=' . intval($photo['nodeid']),
						'thumb' => 'filedata/fetch?' . $queryVar . '=' . intval($photo['nodeid']) . "&thumb=1",
						'links' => $phraseApi->getPhrase('photos_by_x_in_y_linked', array($userLink, $photo['authorname'],
							$topicLink, htmlspecialchars($titleVm) )) . "<br />\n"
					);
				}
				$this->sendAsJson($gallery);
				return;

			default:
				$galleryData = $api->callApi('content_gallery', 'getContent', array('nodeid' => $nodeid));
				if (!empty($galleryData) AND !empty($galleryData[$nodeid]['photo']))
				{
					foreach($galleryData[$nodeid]['photo'] AS $photo)
					{
						$userLink =  vB5_Template_Options::instance()->get('options.frontendurl') . $api->callApi('route', 'getUrl', array('route' => 'profile',
							'data' => array('userid' => $photo['userid'], 'username' => $photo['authorname']), 'extra' => array()));
						$gallery['photos'][] = array(
							'title' => $photo['title'],
							'htmltitle' => $photo['htmltitle'],
							'url' => 'filedata/fetch?photoid=' . intval($photo['nodeid']),
							'thumb' => 'filedata/fetch?photoid=' . intval($photo['nodeid']) . "&thumb=1",
							'links' => $phraseApi->getPhrase('photos_by_x_in_y_linked', array($userLink, $photo['authorname'], 'javascript:$(\'#slideshow-dialog\').dialog(\'close\');void(0);', htmlspecialchars($photo['startertitle']))) . "<br />\n",
						);
					}
					$this->sendAsJson($gallery);
				}
				return;
		}
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 91450 $
|| #######################################################################
\*=========================================================================*/
