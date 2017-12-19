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
 * vB_Api_Vb4_private
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Vb4_private extends vB_Api
{
	public function movepm($messageids, $folderid)
	{
		$cleaner = vB::getCleaner();
		$messageids = $cleaner->clean($messageids, vB_Cleaner::TYPE_STR);
		$folderid = $cleaner->clean($folderid, vB_Cleaner::TYPE_UINT);

		$folders = vB_Api::instance('content_privatemessage')->listFolders();

		if ($folders === null OR !empty($folders['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($folders);
		}


		if($folderid == -1)
		{
			$folderid = array_search('Sent Items', $folders);
		}
		else if($folderid == 0)
		{
			$folderid = array_search('Inbox', $folders);
		}

		if (empty($messageids) || empty($folderid))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		$pm = unserialize($messageids);

		if (empty($pm))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}
		foreach ($pm as $pmid => $nothing)
		{
			$result = vB_Api::instance('content_privatemessage')->moveMessage($pmid, $folderid);
			if ($result === null || isset($result['errors']))
			{
				return vB_Library::instance('vb4_functions')->getErrorResponse($result);
			}
		}
		return array('response' => array('errormessage' => array('pm_messagesmoved')));
	}

	public function managepm($pm, $dowhat, $folderid = null)
	{
		$cleaner = vB::getCleaner();
		$pm = $cleaner->clean($pm, vB_Cleaner::TYPE_ARRAY);
		$dowhat = $cleaner->clean($dowhat, vB_Cleaner::TYPE_STR);
		$folderid = $cleaner->clean($folderid, vB_Cleaner::TYPE_UINT);

		$folders = vB_Api::instance('content_privatemessage')->listFolders();

		if ($folders === null OR !empty($folders['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($folders);
		}


		if($folderid == -1)
		{
			$folderid = array_search('Sent Items', $folders);
		}
		else if($folderid == 0)
		{
			$folderid = array_search('Inbox', $folders);
		}

		if (empty($pm) ||
			empty($dowhat))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		if ($dowhat == 'move')
		{
			if (empty($folderid))
			{
				return array('response' => array('errormessage' => array('invalidid')));
			}
			foreach ($pm as $pmid => $nothing)
			{
				$result = vB_Api::instance('content_privatemessage')->moveMessage($pmid, $folderid);
				if ($result === null || isset($result['errors']))
				{
					return vB_Library::instance('vb4_functions')->getErrorResponse($result);
				}
			}
			return array('response' => array('HTML' => array('messageids' => serialize($pm))));
		}
		else if ($dowhat == 'unread')
		{
			foreach ($pm as $pmid => $nothing)
			{
				$result = vB_Api::instance('content_privatemessage')->setRead($pmid, 0);
				if ($result === null || isset($result['errors']))
				{
					return vB_Library::instance('vb4_functions')->getErrorResponse($result);
				}
			}
			return array('response' => array('errormessage' => array('pm_messagesmarkedas')));
		}
		else if ($dowhat == 'read')
		{
			foreach ($pm as $pmid => $nothing)
			{
				$result = vB_Api::instance('content_privatemessage')->setRead($pmid, 1);
				if ($result === null || isset($result['errors']))
				{
					return vB_Library::instance('vb4_functions')->getErrorResponse($result);
				}
			}
			return array('response' => array('errormessage' => array('pm_messagesmarkedas')));
		}
		else if ($dowhat == 'delete')
		{
			foreach ($pm as $pmid => $nothing)
			{
				$result = vB_Api::instance('content_privatemessage')->deleteMessage($pmid);
				if (isset($result['errors']))
				{
					return vB_Library::instance('vb4_functions')->getErrorResponse($result);
				}
			}

			return array('response' => array('errormessage' => array('pm_messagesdeleted')));
		}
		else
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}
	}

	public function insertpm($message, $title = '', $recipients = '', $replypmid = null)
	{
		$cleaner = vB::getCleaner();
		$message = $cleaner->clean($message, vB_Cleaner::TYPE_STR);
		$title = $cleaner->clean($title, vB_Cleaner::TYPE_STR);
		$recipients = $cleaner->clean($recipients, vB_Cleaner::TYPE_STR);
		$replypmid = $cleaner->clean($replypmid, vB_Cleaner::TYPE_UINT);

		if (empty($message))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		if (!empty($replypmid))
		{
			$pmThread = vB_Api::instance("node")->getNode($replypmid);
			if (!empty($pmThread['errors']) OR empty($pmThread['starter']))
			{
				return array('response' => array('errormessage' => array('invalidid')));
			}

			// respondto
			$data = array(
				'respondto' => $pmThread['starter'],
				'rawtext' => $message,
			);
		}
		else
		{
			if (empty($title) OR empty($recipients))
			{
				return array('response' => array('errormessage' => array('invalidid')));
			}

			$recipients = implode(',', array_map('trim', explode(';', $recipients)));

			$data = array(
				'msgRecipients' => $recipients,
				'title' => $title,
				'rawtext' => $message,
			);
		}

		$result = vB_Api::instance('content_privatemessage')->add($data, array('wysiwyg' => false));

		if ($result === null || isset($result['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($result);
		}
		return array('response' => array('errormessage' => 'pm_messagesent'));
	}

	//VBV-11007
	public function sendemail($pmid, $reason){
		$cleaner = vB::getCleaner();
		$postid = $cleaner->clean($pmid, vB_Cleaner::TYPE_UINT);
		$reason = $cleaner->clean($reason, vB_Cleaner::TYPE_STR);

		if (empty($pmid))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		if (empty($reason))
		{
			return array('response' => array('errormessage' => array('invalidreason')));
		}

		return vB_Api::instance('vb4_report')->sendemail($pmid, $reason);
	}

	public function showpm($pmid)
	{
		/*
			getMessage() & downstream functions seem to be written to expect a starter
			nodeid as the parameter. If given a reply nodeid as the parameter, the result
			doesn't seem quite correct. Particularly it sets the reply's "starter" = true
			even though it's not a starter (in other "node" return arrays, starter would
			hold the starter's nodeid, not a bool) which isn't really helpful.

			This function regularly gets a reply nodeid for $pmid because vB4 didn't really
			have "threads" of private messages and the mobile clients don't have support
			for private message threads yet.

			However we can't just get the starter and call getMessage() on the starter because
			that would mark the entire tree as "read", while we only want this specific node
			to be marked as read.

			Thankfully, the results of getMessage() contains a "startertitle" field, which
			was filled in by the getNodeContent() call from inside of PM Lib's getMessageTree(),
			so we'll use that.
		 */
		$pm = vB_Api::instanceInternal('content_privatemessage')->getMessage($pmid);

		if(empty($pm))
		{
			return array("response" => array("errormessage" => array("invalidid")));
		}

		$pm_response = array();

		$recipients = $this->parseRecipients($pm);

		$title = $pm['message']['title'];
		if (empty($title) AND !empty($pm['message']['startertitle']))
		{
			$title = $pm['message']['startertitle'];
			$title = vB_Phrase::fetchSinglePhrase('re_x', $title);
		}
		// Mobile app doesn't like escaped html entities.
		$title = vB_String::unHtmlSpecialChars($title);
		$username = vB_String::unHtmlSpecialChars($pm['message']['authorname']);

		$pm_response['response']['HTML']['pm'] = array(
			'pmid' => $pmid,
			'fromusername' => $username,
			'title' => $title,
			'recipients' => $recipients,
		);

		$pm_response['response']['HTML']['postbit']['post'] = array(
			'posttime' => $pm['message']['publishdate'],
			'username' => $username,
			'title' => $title,
			'avatarurl' => !empty($pm['message']['senderAvatar']) ? $pm['message']['senderAvatar']['avatarpath'] : '',
			'message' => $this->parseBBCodeMessage($pm['message']),
			'message_plain' => strip_bbcode($pm['message']['rawtext']),
			'message_bbcode' => $pm['message']['rawtext'],
		);

		return $pm_response;
	}

	protected function parseRecipients($pm)
	{
		$pm = $pm['message'];
		if (!empty($pm['recipients']))
		{
			$recipients = array();
			foreach ($pm['recipients'] as $recipient)
			{
				$rinfo = vB_Library::instance('user')->fetchUserinfo($recipient['userid']);
				$recipients[] = $rinfo['username'];
			}
			return implode(';', $recipients);
		}
		else
		{
			return $pm['username'];
		}
	}

	public function editfolders()
	{
		$folders = vB_Api::instanceInternal('content_privatemessage')->fetchSummary();

		$custom_folders = array('response' => array('HTML' => array('editfolderbits' => array())));
		foreach($folders['folders']['customFolders'] as $folder)
		{
			$custom_folders['response']['HTML']['editfolderbits'][] = array(
				'folderid' => $folder['folderid'],
				'foldername' => $folder['title'],
				'foldertotal' => $folder['qty']
			);
		}

		return $custom_folders;
	}

	public function messagelist($folderid = 0, $perpage = 10, $pagenumber = 1, $sort = 'date', $order = 'desc')
	{
		//
		//  vB4 folders are:
		//      0   = Inbox
		//      -1  = Sent
		//      N   = Custom
		//

		$folders = vB_Api::instance('content_privatemessage')->listFolders();
		if ($folders === null OR !empty($folders['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($folders);
		}

		$inbox = false;
		$skipSelfPMs = false;
		switch($folderid)
		{
			case -1:
				$folderid = array_search('Sent Items', $folders);
				break;
			case 0:
				$skipSelfPMs = true;
				$inbox = true;
				$folderid = array_search('Inbox', $folders);
				break;
			default:
				// otherwise, assume it's custom folder and folderid is valid.
				break;
		}

		$userid =  vB::getCurrentSession()->get('userid');

		// blocked users
		$options = vB::getDatastore()->getValue('options');
		$blocked = array();
		if (trim($options['globalignore']) != '')
		{
			$blocked = preg_split('#\s+#s', $options['globalignore'], -1, PREG_SPLIT_NO_EMPTY);
			//the user can always see their own posts, so if they are in the blocked list we remove them
			$bbuserkey = array_search($userid , $blocked);

			if ($bbuserkey !== FALSE AND $bbuserkey !== NULL)
			{
				unset($blocked["$bbuserkey"]);
			}
		}
		if (empty($blocked))
		{
			$blocked = array(-1); // there shouldn't be any PMs from guests.
		}
		//$blocked[] = 1; // DEBUG

		if ($skipSelfPMs)
		{
			$blocked[] = $userid;
		}

		$params = array(
				'userid' => $userid,
				'folderid' => $folderid,
				'skipSenders' => $blocked,
				'sort' => $sort,
				'sortDir' => $order,
				vB_dB_Query::PARAM_LIMIT => $perpage,
				vB_dB_Query::PARAM_LIMITPAGE => $pagenumber,
		);

		$assertor = vB::getDbAssertor();
		$messageQry = $assertor->assertQuery('vBForum:listFlattenedPrivateMessages', $params);


		$messages = array();
		foreach ($messageQry AS $message)
		{
			if (empty($message['previewtext']))
			{
				$message['previewtext'] = vB_String::getPreviewText($message['rawtext']);
			}
			unset ($message['rawtext']);

			if ($message['nodeid'] != $message['starter'])
			{
				// this is a reply. Add a RE: prefix.
				$message['title'] = vB_Phrase::fetchSinglePhrase('re_x', $message['title']);
			}

			$messages[] = $message;
		}

		/*
			In vB4, each message was a stand alone item.
			A reply was usually sent to others but not yourself, so it wouldn't be shown (but if you added yourself to the reply recipient, persumably it'd show up)
			In vB5, each message is part of a message thread.
			Since only thread starters are listed in listMessages, we need to go through each starter and fetch the replies,
			and include all the replies. We could potentially check if the replier is the current user and skip that one, but I don't think it's a good idea to put in
			those weird work arounds.
			Another issue is that "sent" starters are excluded from listMessages() until someone replies to it in vB5 due to weird inconsistencies with the sentto record handling.
			I'm going to ignore all those, and just straight up fetch all replies, and flatten the structure.

			One thing that we need to worry about is that the date order might be off.
			How do we wanna handle multiple threads whose replies are interspersed in terms of dates
		 */
		// TODO: when should a PM be read when fetched from MAPI?

		$final_messages = array();
		foreach($messages as $key => $message)
		{
			$final_messages[] = $this->parseMessage($message);
		}

		$totalCount = $assertor->getRow('vBForum:countFlattenedPrivateMessages', $params);
		if (isset($totalCount['total']))
		{
			$totalCount = $totalCount['total'];
		}
		else
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($totalCount);
		}

		$page_nav = vB_Library::instance('vb4_functions')->pageNav($pagenumber, $perpage, $totalCount);

		$response = array();
		$response['response']['HTML']['folderid'] = $inbox? 0 : $folderid;
		$response['response']['HTML']['pagenav'] = $page_nav;
		$response['response']['HTML']['messagelist_periodgroups']['messagelistbits'] = $final_messages;

		return $response;
	}

	private function parseMessage($message)
	{
		return array(
			'pm' => array(
				'pmid' => $message['nodeid'],
				'sendtime' => $message['publishdate'],
				'title' => vB_String::unHtmlSpecialChars($message['title'] ? $message['title'] : $message['previewtext']),
				'statusicon' => $message['msgread'] ? 'old' : 'new'
			),
			'userbit' => array(
				'userinfo' => array(
					'userid' => $message['userid'],
					'username' => vB_String::unHtmlSpecialChars($message['username']),
				),
			),
			'show' => array(
				'unread' => $message['msgread'] ? 0 : 1
			)
		);
	}

	private function parseBBCodeMessage($message)
	{
		$this->bbcode_parser = new vB_Library_BbCode(true, true);
		$this->bbcode_parser->setAttachments($message['attach']);
		$this->bbcode_parser->setParseUserinfo($message['userid']);

		$authorContext = vB::getUserContext($message['userid']);

		$canusehtml = $authorContext->getChannelPermission('forumpermissions2', 'canusehtml', $message['parentid']);
		require_once DIR . '/includes/functions.php';

		return fetch_censored_text($this->bbcode_parser->doParse(
			$message['rawtext'],
			$canusehtml,
			true,
			true,
			$authorContext->getChannelPermission('forumpermissions', 'cangetattachment', $message['parentid']),
			true
		));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 94333 $
|| #######################################################################
\*=========================================================================*/
