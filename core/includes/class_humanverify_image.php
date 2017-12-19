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
* Human Verification class for Image Verification
*
* @package 		vBulletin
* @version		$Revision: 89898 $
* @date 		$Date: 2016-08-02 17:22:22 -0700 (Tue, 02 Aug 2016) $
*
*/
class vB_HumanVerify_Image extends vB_HumanVerify_Abstract
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
		if (!is_array($input) OR empty($input['input']))
		{
			$this->error = 'humanverify_missing';
			return false;
		}

		$input['input'] = trim(str_replace(' ', '', $input['input']));

		if ($this->delete_token($input['hash'], $input['input']))
		{
			return true;
		}
		else
		{
			$this->error = 'humanverify_image_wronganswer';
			return false;
		}
	}

	/**
	* Call this class' answer function via a middleman since it has an argument
	*
	* @return	string
	*/
	protected function fetch_answer()
	{
		return $this->fetch_answer_string();
	}

	/**
	* Generate a random string for image verification
	*
	* @param	int		Length of result
	*
	* @return	string
	*/
	private function fetch_answer_string($length = 6)
	{
		$somechars = '234689ABCEFGHJMNPQRSTWY';
		$morechars = '234689ABCEFGHJKMNPQRSTWXYZabcdefghjkmnpstwxyz';
		$word = '';

		for ($x = 1; $x <= $length; $x++)
		{
			$chars = ($x <= 2 OR $x == $length) ? $morechars : $somechars;
			$number = vbrand(1, strlen($chars));
			$word .= substr($chars, $number - 1, 1);
	 	}

	 	return $word;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 89898 $
|| #######################################################################
\*=========================================================================*/
