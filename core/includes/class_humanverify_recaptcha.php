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
* @version		$Revision: 89898 $
* @date 		$Date: 2016-08-02 17:22:22 -0700 (Tue, 02 Aug 2016) $
*
*/
class vB_HumanVerify_Recaptcha extends vB_HumanVerify_Abstract
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
		if (!empty($input['recaptcha_response_field']) AND !empty($input['recaptcha_challenge_field']))
		{
			// Contact recaptcha.net
			$private_key = vB::getDatastore()->getOption('hv_recaptcha_privatekey');
			if (!$private_key)
			{
				$private_key = '6LfHsgMAAAAAACYsFwZz6cqcG-WWnfay7NIrciyU';
			}

			$query = array(
				'privatekey=' . urlencode($private_key),
				'remoteip=' . urlencode(vB::getRequest()->getIpAddress()),
				'challenge=' . urlencode($input['recaptcha_challenge_field']),
				'response=' . urlencode($input['recaptcha_response_field']),
			);

			$vurl = new vB_vURL();
			$vurl->set_option(VURL_URL, 'http://api-verify.recaptcha.net/verify');
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
				$result = explode("\n", $result);
				if ($result[0] === 'true')
				{
					return true;
				}

				switch ($result[1])
				{
					case 'invalid-site-public-key':
						$this->error = 'humanverify_recaptcha_publickey';
						break;
					case 'invalid-site-private-key':
						$this->error = 'humanverify_recaptcha_privatekey';
						break;
					case 'invalid-referrer':
						$this->error = 'humanverify_recaptcha_referrer';
						break;
					case 'invalid-request-cookie':
						$this->error = 'humanverify_recaptcha_challenge';
						break;
					case 'verify-params-incorrect':
						$this->error = 'humanverify_recaptcha_parameters';
						break;
					default:
						$this->error = 'humanverify_image_wronganswer';
				}

				return false;
			}
		}
		else
		{
			$this->error = 'humanverify_image_wronganswer';
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
|| # CVS: $RCSfile$ - $Revision: 89898 $
|| #######################################################################
\*=========================================================================*/
