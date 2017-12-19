<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
/*
if (!isset($GLOBALS['vbulletin']->db))
{
	exit;
}
*/

class vB_Upgrade_423 extends vB_Upgrade_Version
{
	/*Constants=====================================================================*/

	/*Properties====================================================================*/

	/**
	* The short version of the script
	*
	* @var	string
	*/
	public $SHORT_VERSION = '423';

	/**
	* The long version of the script
	*
	* @var	string
	*/
	public $LONG_VERSION  = '4.2.3';

	/**
	* Versions that can upgrade to this script
	*
	* @var	string
	*/
	public $PREV_VERSION = '4.2.3 Release Candidate 1';

	/**
	* Beginning version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_STARTS = '';

	/**
	* Ending version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_ENDS   = '';

	/* 
	Step 1
	Check attachment refcounts and fix any that are broken.
	This step isnt necessasry, it duplicates 4.2.4 Beta 1 Step 1.  
	*/
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017 : $Revision: 92140 $
|| # $Date: 2016-12-31 04:26:15 +0000 (Sat, 31 Dec 2016) $
|| ####################################################################
\*======================================================================*/
