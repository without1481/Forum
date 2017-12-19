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

class vB_Upgrade_425b2 extends vB_Upgrade_Version
{
	/*Constants=====================================================================*/

	/*Properties====================================================================*/

	/**
	* The short version of the script
	*
	* @var	string
	*/
	public $SHORT_VERSION = '425b2';

	/**
	* The long version of the script
	*
	* @var	string
	*/
	public $LONG_VERSION  = '4.2.5 Beta 2';

	/**
	* Versions that can upgrade to this script
	*
	* @var	string
	*/
	public $PREV_VERSION = '4.2.5 Beta 1';

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

	The following steps were moved to vB5 ;
	
	Step 1 - Moved to 5.3.1 Alpha 3, Step 1
	Step 3 - Moved to 5.3.1 Alpha 3, Step 2
	Step 4 - Moved to 5.3.1 Alpha 3, Step 3
	Step 5 - Moved to 5.3.1 Alpha 3, Step 4

	Step 2, and Steps 6 to 16 have been removed.
	They perform actions not necessary for a vB5 upgrade.

	*/
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 35750 $
|| ####################################################################
\*======================================================================*/
