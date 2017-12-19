<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 5 Presentation Configuration                           # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is �2000-2012 vBulletin Solutions Inc. # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

/*-------------------------------------------------------*\
| ****** NOTE REGARDING THE VARIABLES IN THIS FILE ****** |
+---------------------------------------------------------+
| When making changes to the file, the edit should always |
| be to the right of the = sign between the single quotes |
| Default: $config['admincpdir'] = 'admincp';             |
| Example: $config['admincpdir'] = 'myadmin';  GOOD!      |
| Example: $config['myadmin'] = 'admincp'; BAD!           |
\*-------------------------------------------------------*/


    //    ****** System Paths ******

    // This setting allows you to change the name of the admin folder
$config['admincpdir'] = 'admincp';

    //    ****** Cookie Settings ******
    // These are cookie related settings.
    // This Setting allows you to change the cookie prefix
$config['cookie_prefix'] = 'bb';


//    ****** Special Settings ******
// These settings are only used in some circumstances
// Please do not edit if you are not sure what they do.

// You can ignore this setting for right now.
$config['cookie_enabled'] = true;

$config['report_all_php_errors'] = false;
$config['no_template_notices'] = true;

// This setting should never be used on a live site
$config['no_js_bundles'] = false;

// This setting enables debug mode, it should NEVER be used on a live site
$config['debug'] = false;

// Assumes default location of core. 
// These are the system paths and folders for your vBulletin files
// This setting is for where your vbulletin core folder is
$config['core_path'] = realpath(dirname(__FILE__)) . '/core';

$config['php_sessions'] = false;

/*======================================================================*\
|| ####################################################################
|| # CVS: $RCSfile$ - 
|| ####################################################################
\*======================================================================*/

