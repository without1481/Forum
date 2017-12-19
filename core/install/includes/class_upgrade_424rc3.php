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

class vB_Upgrade_424rc3 extends vB_Upgrade_Version
{
	/*Constants=====================================================================*/

	/*Properties====================================================================*/

	/**
	* The short version of the script
	*
	* @var	string
	*/
	public $SHORT_VERSION = '424rc3';

	/**
	* The long version of the script
	*
	* @var	string
	*/
	public $LONG_VERSION  = '4.2.4 Release Candidate 3';

	/**
	* Versions that can upgrade to this script
	*
	* @var	string
	*/
	public $PREV_VERSION = '4.2.4 Release Candidate 2';

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
	Update Read Marking Option
	This sets everyone to use DB marking as we removed the option in 4.2.5.
	I believe vB5 still has this option so left this in so upgraded sites will continue to 
	use the option consistantly (vB5 really should remove the cookie based system as well).
	*/
	public function step_1()
	{
		$this->run_query(
			$this->phrase['version']['424rc3']['update_marking'],
			"UPDATE ".TABLE_PREFIX."setting SET value = '2' WHERE varname = 'threadmarking'"
		);
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017 : $Revision: 92674 $
|| # $Date: 2017-01-30 02:09:40 +0000 (Mon, 30 Jan 2017) $
|| ####################################################################
\*======================================================================*/
