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

class vB_DataManager_StyleVarTextDecoration extends vB_DataManager_StyleVar
{
	var $childfields = array(
		'none'					=> array(vB_Cleaner::TYPE_BOOL,			vB_DataManager_Constants::REQ_NO),
		'underline'				=> array(vB_Cleaner::TYPE_BOOL,			vB_DataManager_Constants::REQ_NO),
		'overline'				=> array(vB_Cleaner::TYPE_BOOL,			vB_DataManager_Constants::REQ_NO),
		'line-through'			=> array(vB_Cleaner::TYPE_BOOL,			vB_DataManager_Constants::REQ_NO),	
		'stylevar_none'			=> array(vB_Cleaner::TYPE_STR,			vB_DataManager_Constants::REQ_NO,		vB_DataManager_Constants::VF_METHOD,	'verify_value_stylevar'),
		'stylevar_underline'	=> array(vB_Cleaner::TYPE_STR,			vB_DataManager_Constants::REQ_NO,		vB_DataManager_Constants::VF_METHOD,	'verify_value_stylevar'),
		'stylevar_overline'		=> array(vB_Cleaner::TYPE_STR,			vB_DataManager_Constants::REQ_NO,		vB_DataManager_Constants::VF_METHOD,	'verify_value_stylevar'),
		'stylevar_line-through'	=> array(vB_Cleaner::TYPE_STR,			vB_DataManager_Constants::REQ_NO,		vB_DataManager_Constants::VF_METHOD,	'verify_value_stylevar'),
	);

	public $datatype = 'TextDecoration';
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 89092 $
|| #######################################################################
\*=========================================================================*/
