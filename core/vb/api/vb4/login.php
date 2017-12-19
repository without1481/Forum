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
 * vB_Api_Vb4_register
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Vb4_login extends vB_Api
{
	/**
	 * Login with facebook logged user
	 *
	 * @param  [string] $signed_request [fb info]
	 * @return [array]                  [response -> errormessage and session params]
	 */
	public function facebook($signed_request, $devicetoken = null)
	{
		$cleaner = vB::getCleaner();
		$signed_request = $cleaner->clean($signed_request, vB_Cleaner::TYPE_STR);

		$user_api = vB_Api::instance('user');
		$loginInfo = $user_api->loginExternal('facebook', array('signedrequest' => $signed_request));

		if (empty($loginInfo) || isset($loginInfo['errors']))
		{
			//the api doesn't allow us to be that specific about our errors here.
			//and the app gets very cranky if the login returns an unexpected error code
			return array('response' => array('errormessage' => array('badlogin_facebook')));
		}

		// Update may throw an exception if devicetoken is longer than allowed by DB.
		try
		{
			// Take care of push notification device token registration (if there is one)
			$updateTokenResult = vB_Library::instance('fcmessaging')->updateDeviceToken($devicetoken);
		}
		catch (Exception $e)
		{
			// todo
		}

		$result = array(
			'session' => array(
				'dbsessionhash' => $loginInfo['login']['sessionhash'],
				'userid' => $loginInfo['login']['userid'],
			),
			'response' => array(
				'errormessage' => array('redirect_login')
			),
		);

		return $result;
	}


	/**
	 * Login. Wraps user.login (because we need to do some mapi specific tasks)
	 *
	 * @param  [string] $signed_request [fb info]
	 * @return [array]                  [response -> errormessage and session params]
	 */
	public function login(
		$vb_login_username,
		$vb_login_password = null,
		$vb_login_md5password = null,
		$vb_login_md5password_utf = null,
		$devicetoken = null
	)
	{
		$userAPI = vB_Api::instanceInternal('user');
		$loginResult = $userAPI->login(
			$vb_login_username,
			$vb_login_password,
			$vb_login_md5password,
			$vb_login_md5password_utf
			// logintype = null
		);

		// Update may throw an exception if devicetoken is longer than allowed by DB.
		try
		{
			// Take care of push notification device token registration (if there is one)
			$updateTokenResult = vB_Library::instance('fcmessaging')->updateDeviceToken($devicetoken);
		}
		catch (Exception $e)
		{
			// todo
		}

		// This will be transformed into the output that mobile client expects by VB_Api::map_vb5_output_to_vb4()
		// called by core/api.php
		return $loginResult;
	}

	public function logout()
	{
		// Take care of push notification device token removal.
		// Do it before we lose session.
		vB_Library::instance('fcmessaging')->removeDeviceToken();

		$logoutResult = vB_Api::instanceInternal('user')->logout();


		// This will be transformed into the output that mobile client expects by VB_Api::map_vb5_output_to_vb4()
		// called by core/api.php
		return $logoutResult;
	}
}
/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 94313 $
|| #######################################################################
\*=========================================================================*/
