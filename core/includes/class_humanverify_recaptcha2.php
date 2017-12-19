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
* Human Verification class for reCAPTCHA Verification (http://recaptcha.net)
*
* @package 		vBulletin
* @version		$Revision: 86409 $
* @date 		$Date: 2015-12-29 10:32:25 -0800 (Tue, 29 Dec 2015) $
*
*/
class vB_HumanVerify_Recaptcha2 extends vB_HumanVerify_Abstract
{
	/**
	* Verify is supplied token/reponse is valid
	*
	*	@param	array	Values given by user 'input' and 'hash'
	*
	* @return	bool
	*/
	public function verify_token($input)
	{
		if(!empty($input['g-recaptcha-response']))
		{
			$private_key = vB::getDatastore()->getOption('hv_recaptcha_privatekey');

			$query = array(
				'secret=' . urlencode($private_key),
				'remoteip=' . urlencode(vB::getRequest()->getIpAddress()),
				'response=' . urlencode($input['g-recaptcha-response']),
			);

			$vurl = new vB_vURL();
			$vurl->set_option(VURL_URL, 'https://www.google.com/recaptcha/api/siteverify');
			$vurl->set_option(VURL_USERAGENT, 'vBulletin ' . FILE_VERSION);
			$vurl->set_option(VURL_POST, 1);
			$vurl->set_option(VURL_POSTFIELDS, implode('&', $query));
			$vurl->set_option(VURL_RETURNTRANSFER, 1);
			$vurl->set_option(VURL_CLOSECONNECTION, 1);

			if (($result = $vurl->exec()) === false)
			{
				$this->error = 'humanverify_recaptcha_unreachable';
				return false;
			}
			else
			{
				$result = json_decode($result, true);
				if ($result['success'] === true)
				{
					return true;
				}

				switch ($result['error-codes'][0])
				{
					case 'missing-input-secret':
					case 'invalid-input-secret':
						$this->error = 'humanverify_recaptcha_privatekey';
						break;
					case 'missing-input-response':
					case 'invalid-input-response ':
					default:
						$this->error = 'humanverify_recaptcha_parameters';
						break;
				}

				return false;
			}
		}
		else
		{
			$this->error = 'humanverify_recaptcha_parameters';
			return false;
		}
	}

	/**
	* expected answer - with this class, we don't know the answer
	*
	* @return	string
	*/
	protected function fetch_answer()
	{
		return '';
	}

	/**
	 * generate token - Normally we want to generate a token to validate against. However,
	 * 		Recaptcha is doing that work for us.
	 *
	 * @param	boolean	Delete the previous hash generated
	 *
	 * @return	array	an array consisting of the hash, and the answer
	 */
	public function generate_token($deletehash = true)
	{
		return array(
			'hash' => '',
			'answer' => '',
		);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 86409 $
|| #######################################################################
\*=========================================================================*/
