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
* General frontend bootstrapping class. As this is designed to be as backwards
* compatible as possible, there are loads of global variables. Beware!
*
* @package	vBulletin
*/
class vB_Bootstrap
{
	/**
	* A particular style ID to force. If specified, it will be used even if disabled.
	*
	* @var	int
	*/
	protected $force_styleid = 0;

	/**
	* Determines the called actions
	*
	* @var	array
	*/
	protected $called = array(
		'style'    => false,
		'template' => false
	);

	/**
	* A list of datastore entries to cache.
	*
	* @var	array
	*/
	public $datastore_entries = array();

	/**
	* A list of templates (names) that should be cached. Does not include
	* globally cached templates.
	*
	* @var	array
	*/
	public $cache_templates = array();

	// ============ MAIN BOOTSTRAPPING FUNCTIONS ===============

	/**
	* General bootstrap wrapper. This can be used to do virtually all of the
	* work that you'd usually want to do at the beginning. Style and template
	* setup are deferred until first usage.
	*/
	public function bootstrap()
	{
		global $VB_API_REQUESTS;

		$this->init();

		$this->load_language();
		$this->load_permissions();

		$this->read_input_context();

		if (!defined('NOCHECKSTATE') AND (!VB_API OR $VB_API_REQUESTS['api_m'] != 'api_init'))
		{
			$this->check_state();
		}

		// Legacy Hook 'global_bootstrap_complete' Removed //
	}

	/**
	* Basic initialization of things like DB, session, etc.
	*/
	public function init()
	{
		global $vbulletin, $db, $show;

		$specialtemplates = $this->datastore_entries;

		$cwd = getcwd();

		if(is_link($cwd))
		{
			$cwd = dirname($_SERVER["SCRIPT_FILENAME"]);
		}
		else if (empty($cwd))
		{
			$cwd = '.';
		}

		define('CWD', $cwd);

		if (!defined('VB_API'))
		{
			define('VB_API', false);
		}

		require_once(CWD . '/includes/init.php');

		// Legacy Hook 'global_bootstrap_init_start' Removed //

		if (!defined('VB_ENTRY'))
		{
			define('VB_ENTRY', 1);
		}

		// Set Display of Ads to true - Set to false on non content pages
		if (!defined('CONTENT_PAGE'))
		{
			define('CONTENT_PAGE', true);
		}

		// Legacy Hook 'global_bootstrap_init_complete' Removed //
	}

	/**
	* Reads some context based on general input information
	*/
	public function read_input_context()
	{
		global $vbulletin;

		$vbulletin->input->clean_array_gpc('r', array(
			'referrerid' => vB_Cleaner::TYPE_UINT,
			'a'          => vB_Cleaner::TYPE_STR,
			'nojs'       => vB_Cleaner::TYPE_BOOL
		));

		$vbulletin->input->clean_array_gpc('p', array(
			'ajax' => vB_Cleaner::TYPE_BOOL,
		));

	}

	/**
	* Loads permissions for the currently logged-in user.
	*/
	public function load_permissions()
	{
		global $vbulletin;
		cache_permissions($vbulletin->userinfo);
	}

	/**
	* Loads the language information for the logged-in user.
	*/
	public function load_language()
	{
		global $vbulletin;

		fetch_options_overrides($vbulletin->userinfo);
		fetch_time_data();

		global $vbphrase;
		// Load language if we're not in the API or the API asks for it
		if (!VB_API OR (defined('VB_API_LOADLANG') AND VB_API_LOADLANG === true))
		{
			$vbphrase = init_language();

			// If in API, disable "Directional Markup Fix" from language options. API doesn't need it.
			if (VB_API AND !empty($vbulletin->userinfo['lang_options']))
			{
				if (is_numeric($vbulletin->userinfo['lang_options']))
				{
					$vbulletin->userinfo['lang_options'] -= $vbulletin->bf_misc_languageoptions['dirmark'];
				}
				else if (is_array($vbulletin->userinfo['lang_options']) AND isset($vbulletin->userinfo['lang_options']['dirmark']))
				{
					unset($vbulletin->userinfo['lang_options']['dirmark']);
				}
			}
		}
		else
		{
			$vbphrase = array();
		}

		// set a default username
		if ($vbulletin->userinfo['username'] == '')
		{
			$vbulletin->userinfo['username'] = $vbphrase['unregistered'];
		}
	}

	/**
	* Loads style information (selected style and style vars)
	*/
	public function load_style()
	{
		if ($this->called('style') AND !(defined('UNIT_TESTING') AND UNIT_TESTING === true))
		{
			return;
		}
		$this->called['style'] = true;

		global $style;
		$style = $this->fetch_style_record($this->force_styleid);
		define('STYLEID', $style['styleid']);

		global $vbulletin;
		$vbulletin->stylevars = unserialize($style['newstylevars']);
		fetch_stylevars($style, $vbulletin->userinfo);
	}

	/**
	* Checks the state of the request to make sure that it's valid and that
	* we have the necessary permissions to continue. Checks things like
	* CSRF and banning.
	*/
	public function check_state()
	{
		global $vbulletin, $show, $VB_API_REQUESTS;

		if (defined('CSRF_ERROR'))
		{
			define('VB_ERROR_LITE', true);

			$ajaxerror = $vbulletin->GPC['ajax'] ? '_ajax' : '';

			switch (CSRF_ERROR)
			{
				case 'missing':
					standard_error(fetch_error('security_token_missing'));
					break;

				case 'guest':
					standard_error(fetch_error('security_token_guest' . $ajaxerror));
					break;

				case 'timeout':
					standard_error(fetch_error('security_token_timeout' . $ajaxerror));
					break;

				case 'invalid':
				default:
					standard_error(fetch_error('security_token_invalid'));
			}
			exit;
		}

		// #############################################################################
		// check to see if server is too busy. this is checked at the end of session.php
		if (
			$this->server_overloaded() AND
			!($vbulletin->userinfo['permissions']['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['cancontrolpanel']) AND
			THIS_SCRIPT != 'login'
		)
		{
			standard_error(fetch_error('toobusy'));
		}

		// #############################################################################
		// check that board is active - if not admin, then display error
		if (
			!defined('BYPASS_FORUM_DISABLED') AND
			!$vbulletin->options['bbactive'] AND
			!in_array(THIS_SCRIPT, array('login', 'css'))	AND
			!($vbulletin->userinfo['permissions']['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['cancontrolpanel'])
		)
		{
			if (defined('DIE_QUIETLY'))
			{
				exit;
			}

			if (defined('VB_API') AND VB_API === true)
			{
				standard_error(fetch_error('bbclosed', $vbulletin->options['bbclosedreason']));
			}
			else
			{
				// If this is a post submission from an admin whose session timed out, give them a chance to log back in and save what they were working on. See bug #34258
				if (
					strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' AND
					!empty($_POST) AND
					!$vbulletin->userinfo['userid'] AND
					!empty($_COOKIE[COOKIE_PREFIX . 'cpsession'])
				)
				{
					define('VB_ERROR_PERMISSION', true);
				}

				$show['enableforumjump'] = true;
				unset($vbulletin->db->shutdownqueries['lastvisit']);
				// unregister in the assertor
				vB::getDbAssertor()->unregisterShutdownQuery('lastvisit');

				require_once(DIR . '/includes/functions_misc.php');
				eval('standard_error("' . make_string_interpolation_safe(str_replace("\\'", "'", addslashes($vbulletin->options['bbclosedreason']))) . '");');
			}
		}

		// #############################################################################
		// password expiry system
		if ($vbulletin->userinfo['userid'] AND $vbulletin->userinfo['permissions']['passwordexpires'])
		{
			$passworddaysold = floor((TIMENOW - $vbulletin->userinfo['passworddate']) / 86400);

			if ($passworddaysold >= $vbulletin->userinfo['permissions']['passwordexpires'])
			{
				if ((THIS_SCRIPT != 'login' AND THIS_SCRIPT != 'profile' AND THIS_SCRIPT != 'ajax')
					OR (THIS_SCRIPT == 'profile' AND $_REQUEST['do'] != 'editpassword' AND $_POST['do'] != 'updatepassword')
					OR (THIS_SCRIPT == 'ajax' AND $_REQUEST['do'] != 'imagereg' AND $_REQUEST['do'] != 'securitytoken' AND $_REQUEST['do'] != 'dismissnotice')
				)
				{
					standard_error(fetch_error('passwordexpired',
						$passworddaysold,
						vB::getCurrentSession()->get('sessionurl')
					));
				}
				else
				{
					$show['passwordexpired'] = true;
				}
			}
		}
		else
		{
			$show['passwordexpired'] = false;
		}

		// #############################################################################
		// check required profile fields
		if (vB::getCurrentSession()->get('profileupdate') AND THIS_SCRIPT != 'login' AND THIS_SCRIPT != 'profile' AND
			!VB_API AND !vB::getUserContext()->isAdministrator())
		{
			standard_error(fetch_error('updateprofilefields', vB::getCurrentSession()->get('sessionurl')));
		}

		// #############################################################################
		// check permission to view forum
		if (!$this->has_global_view_permission())
		{
			if (defined('DIE_QUIETLY'))
			{
				exit;
			}
			else
			{
				print_no_permission();
			}
		}

		// #############################################################################
		// check for IP ban on user
		verify_ip_ban();

		// Legacy Hook 'global_state_check' Removed //
	}

	// ============ HELPER FUNCTIONS ===============

	/**
	* Determines whether a particular step of the bootstrapping has been called.
	*
	* @param	string	Name of the step
	*
	* @return	bool	True if called
	*/
	public function called($step)
	{
		return !empty($this->called[$step]);
	}

	/**
	* Determines the style that should be used either by parameter or permissions
	* and then fetches that information
	*
	* @param	integer	A style ID to force (ignoring permissions). 0 to not force any.
	*
	* @return	array	Array of style information
	*/
	protected function fetch_style_record($force_styleid = 0)
	{
		global $vbulletin;

		$userselect = (defined('THIS_SCRIPT') AND THIS_SCRIPT == 'css') ? true : false;

		// is style in the forum/thread set?
		if ($force_styleid)
		{
			// style specified by forum
			$styleid = $force_styleid;
			$vbulletin->userinfo['styleid'] = $styleid;
			$userselect = true;
		}
		else if ($vbulletin->userinfo['styleid'] > 0 AND ($vbulletin->options['allowchangestyles'] == 1 OR ($vbulletin->userinfo['permissions']['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['cancontrolpanel'])))
		{
			// style specified in user profile
			$styleid = $vbulletin->userinfo['styleid'];
		}
		else
		{
			// no style specified - use default
			$styleid = $vbulletin->options['styleid'];
			$vbulletin->userinfo['styleid'] = $styleid;
		}

		// #############################################################################
		// if user can control panel, allow selection of any style (for testing purposes)
		// otherwise only allow styles that are user-selectable
		$styleid = intval($styleid);
		$style = NULL;

		// Legacy Hook 'style_fetch' Removed //

		if (!is_array($style))
		{
			//call library to allow use of the same cache of styles we'll use elsewhere
			//no API call to get style record (may not be a good idea) so we call the
			//library directly.  This is not any more invasive than the direct
			//query we replaced.
			$styleLib = vB_Library::instance('style');
			$style = $styleLib->fetchStyleRecord($styleid, true);
		}
		return $style;
	}

	/**
	* Resolves the required templates for a particular action.
	*
	* @param	string	The action chosen
	* @param	array	Array of action-specific templates (for empty action, key 'none')
	* @param	array	List of global templates (always needed)
	*
	* @return	array	Array of required templates
	*/
	public static function fetch_required_template_list($action, $action_templates, $global_templates = array())
	{
		$action = (empty($action) ? 'none' : $action);

		if (!is_array($global_templates))
		{
			$global_templates = array();
		}

		if (!empty($action_templates["$action"]) AND is_array($action_templates["$action"]))
		{
			$global_templates = array_merge($global_templates, $action_templates["$action"]);
		}

		return $global_templates;
	}

	/**
	* Builds the collapse array based on a string representing collapse sections.
	*
	* @param	string	List of collapsed sections
	*
	* @return	array	Array with 3 values set for each collapsed section
	*/
	public static function build_vbcollapse($collapse_string)
	{
		$vbcollapse = array();
		if (!empty($collapse_string))
		{
			$val = preg_split('#\n#', $collapse_string, -1, PREG_SPLIT_NO_EMPTY);
			foreach ($val AS $key)
			{
				$vbcollapse["collapseobj_$key"] = 'display:none;';
				$vbcollapse["collapseimg_$key"] = '_collapsed';
				$vbcollapse["collapsecel_$key"] = '_collapsed';
			}
			unset($val);
		}

		return $vbcollapse;
	}

	/**
	* Determines if the server is over the defined load limits
	*
	* @return	bool
	*/
	protected function server_overloaded()
	{
		global $vbulletin;

		if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' AND $vbulletin->options['loadlimit'] > 0)
		{
			if (!is_array($vbulletin->loadcache) OR $vbulletin->loadcache['lastcheck'] < (TIMENOW - $vbulletin->options['recheckfrequency']))
			{
				update_loadavg();
			}

			if ($vbulletin->loadcache['loadavg'] > $vbulletin->options['loadlimit'])
			{
				return true;
			}
		}

		return false;
	}

	/**
	* Determines if the user has global viewing permissions. There are exceptions
	* for certain scripts (like login) and actions that will always return true.
	*
	* @return	bool
	*/
	protected function has_global_view_permission()
	{
		global $vbulletin;

		if (!(vB::getUserContext()->getChannelPermission('forumpermissions', 'canview', 1)))
		{
			$allowed_scripts = array(
				'register',
				'login',
				'image',
				'sendmessage',
				'subscription',
				'searchindex',
				'ajax'
			);
			if (!in_array(THIS_SCRIPT, $allowed_scripts))
			{
				return false;
			}
			else
			{
				if (THIS_SCRIPT == 'searchindex')
				{
					return true;
				}

				$_doArray = array('contactus', 'docontactus', 'register', 'signup', 'requestemail', 'emailcode', 'activate', 'login', 'logout', 'lostpw', 'emailpassword', 'addmember', 'coppaform', 'resetpassword', 'regcheck', 'checkdate', 'removesubscription', 'imagereg', 'verifyusername');
				if (THIS_SCRIPT == 'sendmessage' AND $_REQUEST['do'] == '')
				{
					$_REQUEST['do'] = 'contactus';
				}
				if (THIS_SCRIPT == 'register' AND $_REQUEST['do'] == '' AND $vbulletin->GPC['a'] == '')
				{
					$_REQUEST['do'] = 'register';
				}
				$_aArray = array('act', 'ver', 'pwd');
				if (!in_array($_REQUEST['do'], $_doArray) AND !in_array($vbulletin->GPC['a'], $_aArray))
				{
					return false;
				}
			}
		}

		return true;
	}

	public function force_styleid($styleid)
	{
		$this->force_styleid = $styleid;
	}
}

/**
* Bootstrapping for forum-specific actions.
*
* @package	vBulletin
*/
class vB_Bootstrap_Forum extends vB_Bootstrap
{
	public function read_input_context()
	{
		global $vbulletin;

		parent::read_input_context();

		//just in case something still relies on these being imported
		//at this point
		$vbulletin->input->clean_array_gpc('r', array(
			'postid'     => vB_Cleaner::TYPE_UINT,
			'threadid'   => vB_Cleaner::TYPE_UINT,
			'forumid'    => vB_Cleaner::TYPE_INT,
			'pollid'     => vB_Cleaner::TYPE_UINT,
		));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 95320 $
|| #######################################################################
\*=========================================================================*/
