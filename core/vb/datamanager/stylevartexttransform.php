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

class vB_DataManager_StyleVarTextTransform extends vB_DataManager_StyleVar
{
	var $childfields = array(
		'texttransform'          => array(vB_Cleaner::TYPE_STR, vB_DataManager_Constants::REQ_NO, vB_DataManager_Constants::VF_METHOD, 'verify_texttransfrom'),
		'stylevar_texttransform' => array(vB_Cleaner::TYPE_STR, vB_DataManager_Constants::REQ_NO, vB_DataManager_Constants::VF_METHOD, 'verify_value_stylevar'),
	);

	public $datatype = 'TextTransform';
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 85294 $
|| #######################################################################
\*=========================================================================*/
