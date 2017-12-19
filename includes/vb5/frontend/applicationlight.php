<?php
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
 * Light version of the application, for fixed routes like getting phrases, options, etc. At the time of writing this, the
 * biggest improvement is skipping the route parsing. There's a lot of processing needed for handling forum-type, channel-type urls
 * that isn't needed for the static routes.
 *
 * @package		vBulletin presentation
 */

class vB5_Frontend_ApplicationLight extends vB5_ApplicationAbstract
{
	//This is just the array of routing-type information.  It defines how the request will be processed.
	protected $application = array();

	//This defines the routes that can be handled by this class.
	protected static $quickRoutes = array
	(
		'ajax/api/options/fetchValues' => array(
			'controller'  => 'options',
			'method'      => 'fetchStatic',
			'static'      => true,
			'handler'     => 'fetchOptions',
			'requirePost' => true,
		),
		'filedata/fetch' => array(
			'static'      => true,
			'handler'     => 'fetchImage',
			'requirePost' => false,
		),
		'external' => array(
			'controller'     => 'external',
			'callcontroller' => true,
			'method'         => 'output',
			'static'         => false,
			'requirePost'    => false,
		),
	);

	/**
	 * @var array Quick routes that match the beginning of the route string
	 */
	protected static $quickRoutePrefixMatch = array(
		'ajax/apidetach' => array(
			'handler'     => 'handleAjaxApiDetached',
			'static'      => false,
			'requirePost' => true,
		), // note, keep this before ajax/api. More specific routes should come before
		// less specific ones, to allow the prefix check to work correctly, see constructor.
		'ajax/api' => array(
			'handler'     => 'handleAjaxApi',
			'static'      => false,
			'requirePost' => true,
		),
		'ajax/render' => array(
			'handler'     => 'callRender',
			'static'      => false,
			'requirePost' => true,
		),
	);

	protected $userid;
	protected $languageid;

	/** Tells whether this class can process this request
	 *
	 * @return bool
	 */
	public static function isQuickRoute()
	{
		if (empty($_REQUEST['routestring']))
		{
			return false;
		}

		if (isset(self::$quickRoutes[$_REQUEST['routestring']]))
		{
			return true;
		}

		foreach (self::$quickRoutePrefixMatch AS $prefix => $route)
		{
			if (substr($_REQUEST['routestring'], 0, strlen($prefix)) == $prefix)
			{
				return true;
			}
		}

		return false;
	}

	/**Standard constructor. We only access applications through init() **/
	protected function __construct()
	{
		if (empty($_REQUEST['routestring']))
		{
			return false;
		}

		if (isset(self::$quickRoutes[$_REQUEST['routestring']]))
		{
			$this->application = self::$quickRoutes[$_REQUEST['routestring']];
			return true;
		}

		foreach (self::$quickRoutePrefixMatch AS $prefix => $route)
		{
			if (substr($_REQUEST['routestring'], 0, strlen($prefix)) == $prefix)
			{
				$this->application = $route;
				return true;
			}
		}

		return false;
	}

	/**
	 * This is the standard way to initialize an application
	 *
	 * @param 	string	location of the configuration file
	 *
	 * @return this application object
	 */
	public static function init($configFile)
	{
		self::$instance = new vB5_Frontend_ApplicationLight();

		$config = vB5_Config::instance();
		$config->loadConfigFile($configFile);
		$corePath = vB5_Config::instance()->core_path;
		//this will be set by vb::init
		//define('CWD', $corePath);
		define('CSRF_PROTECTION', true);
		define('VB_AREA', 'Presentation');
		require_once ($corePath . "/vb/vb.php");
		vB::init();
		vB::setRequest(new vB_Request_WebApi());

		self::$instance->convertInputArrayCharset();
		return self::$instance;
	}

	/**
	 * Executes the application. Normally this means to get some data. We usually return in json format.
	 *
	 * @return bool
	 * @throws vB_Exception_Api
	 */
	public function execute()
	{
		if (empty($this->application))
		{
			throw new vB_Exception_Api('invalid_request');
		}

		// These handlers must require POST request method, but POST requests can accept parameters passed in via
		// both the post body ($_POST) and querystring in the url ($_GET)
		if ($this->application['requirePost'])
		{
			if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
			{
				throw new vB5_Exception('Incorrect HTTP Method. Please use a POST request.');
			}

			// Also require a CSRF token check.
			static::checkCSRF();
		}

		$serverData = array_merge($_GET, $_POST);

		if (!empty($this->application['handler']) AND method_exists($this, $this->application['handler']))
		{
			$app = $this->application['handler'];
			call_user_func(array($this, $app), $serverData);

			return true;
		}
		else if ($this->application['static'])
		{
			//BEWARE- NOT YET TESTED
			$result = Api_InterfaceAbstract::instance()->callApiStatic(
				$this->application['controller'],
				$this->application['method'],
				$serverData,
				true
			);
		}
		else if ($this->application['callcontroller'])
		{
			$response = $this->callController(
				$this->application['controller'],
				$this->application['method']
			);

			// using an array will let us have more control on the response.
			// we can easily extend to support printing different kind of outputs.
			echo $response['response'];

			return true;
		}
		else
		{
			//We need to create a session
			$result = Api_InterfaceAbstract::instance()->callApi(
				$this->application['controller'],
				$this->application['method'],
				$serverData,
				true
			);
		}

		$controller = new vB5_Frontend_Controller();
		$controller->sendAsJson($result);

		return true;
	}

	/**
	 * Calls a controller action and returns the response.
	 *
	 * @param 	string 	Controller name.
	 * @param 	string 	Controller action.
	 *
	 * @return 	array 	Information of controller call:
	 *					- Response => the result from calling the controller action.
	 *
	 */
	private function callController($controller, $action)
	{
		$controller = ucfirst(strtolower($controller));
		$action = ucfirst(strtolower($action));
		$controllerClass = 'vB5_Frontend_Controller_' . $controller;
		$controllerMethod = 'action' . $action;

		if (class_exists($controllerClass) AND method_exists($controllerClass, $controllerMethod))
		{
			$controller = new $controllerClass();
			return array('response' => $controller->$controllerMethod());
		}

		return array('response' => '');
	}

	/**
	 * This gets phrase data from an ajax request.
	 *
	 * @param array Array of server data (from $_POST and/or $_GET, see execute())
	 */
	protected function fetchOptions($serverData)
	{
		$options = Api_Interface_Collapsed::callApiStatic(
			'options',
			'fetchStatic',
			array(
				'options' => $serverData['options'],
			),
			true
		);

		$this->sendAsJson($options);
	}

	/**
	 * Renders a template from an ajax call
	 *
	 * @param array Array of server data (from $_POST and/or $_GET, see execute())
	 */
	protected function callRender($serverData)
	{
		$routeInfo = explode('/', $serverData['routestring']);

		if (count($routeInfo) < 3)
		{
			throw new vB5_Exception_Api('ajax', 'render', array(), 'invalid_request');
		}

		$this->router = new vB5_Frontend_Routing();
		$this->router->setRouteInfo(array(
			'action'          => 'actionRender',
			'arguments'       => $serverData,
			'template'        => $routeInfo[2],
			// this use of $_GET appears to be fine,
			// since it's setting the route query params
			// not sending the data to the template
			// render
			'queryParameters' => $_GET,
		));
		Api_InterfaceAbstract::setLight();

		$this->sendAsJson(vB5_Template::staticRenderAjax($routeInfo[2], $serverData));
	}

	/**
	 * This handles an ajax api call.
	 *
	 * @param array Array of server data (from $_POST and/or $_GET, see execute())
	 */
	protected function handleAjaxApi($serverData)
	{
		$routeInfo = explode('/', $serverData['routestring']);

		if (count($routeInfo) < 4)
		{
			throw new vB5_Exception_Api('ajax', 'api', array(), 'invalid_request');
		}

		//we use : to delineate packages in controller names, but that's a reserved
		//character in the url structure so we use periods in URLs.
		$controller = str_replace('.', ':', $routeInfo[2]);

		$this->sendAsJson(Api_InterfaceAbstract::instance(Api_InterfaceAbstract::API_LIGHT)->callApi(
			$controller,
			$routeInfo[3],
			$serverData,
			true
		));
	}

	/**
	 * This handles an ajax api call, detatched from the current request
	 *
	 * @param array Array of server data (from $_POST and/or $_GET, see execute())
	 */
	protected function handleAjaxApiDetached($serverData)
	{
		// Keep this function in sync with vB5_Frontend_Controller::sendAsJsonAndCloseConnection()
		// TODO: Make the controller function public and have this call it.
		// The main reason I didn't do this now is because there are some differences between this class's
		// sendAsJson() & the controller, and the changes were starting to get a bit too big for this particular
		// JIRA than I was comfortable with.

		//make sure this is a valid request before detaching.
		$routeInfo = explode('/', $serverData['routestring']);
		if (count($routeInfo) < 4)
		{
			throw new vB5_Exception_Api('ajax', 'apidetach', array(), 'invalid_request');
		}

		//if we don't get the api before we close the connection we can end up failing
		//to set cookies, which will cause the entire call to fail.  That is bad.
		$api = Api_InterfaceAbstract::instance(Api_InterfaceAbstract::API_LIGHT);

		$this->sendAsJsonAndCloseConnection();
		//we use : to delineate packages in controller names, but that's a reserved
		//character in the url structure so we use periods in URLs.
		$controller = str_replace('.', ':', $routeInfo[2]);

		//don't do anything with the return, we've already let the broswer go.
		$api->callApi(
			$controller,
			$routeInfo[3],
			$serverData,
			true
		);
	}

	/**
	 * This gets an image
	 *
	 * @param array Array of server data (from $_POST and/or $_GET, see execute())
	 */
	protected function fetchImage($serverData)
	{
		$api = Api_InterfaceAbstract::instance('light');

		$request = array(
			'id'          => 0,
			'type'        => '',
			'includeData' => true,
		);

		if (isset($serverData['type']) AND !empty($serverData['type']))
		{
			$request['type'] = $serverData['type'];
		}
		else if (!empty($serverData['thumb']) AND intval($serverData['thumb']))
		{
			$request['type'] = 'thumb';
		}

		$fileInfo = array();
		if (!empty($serverData['id']) AND intval($serverData['id']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['id']);

			set_error_handler(array($this, 'handleImageError'), E_ALL | E_STRICT ) ;

			// we can have type photo nodes coming in via the id parameter
			// when text.previewimage is used in article listings or the
			// content slider module.
			$nodeInfo = $api->callApi('node', 'getNode', array('nodeid' => $request['id']));
			if(!isset($nodeInfo['errors']))
			{
				$contentType = $api->callApi('contenttype', 'fetchContentTypeClassFromId', array('contenttypeid' => $nodeInfo['contenttypeid']));
				if ($contentType == 'Photo')
				{
					$fileInfo = $api->callApi('content_photo', 'fetchImageByPhotoid', $request);
				}
				else
				{
					$fileInfo = $api->callApi('content_attach', 'fetchImage', $request);
				}
			}
		}
		else if (!empty($serverData['filedataid']) AND intval($serverData['filedataid']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['filedataid']);
			set_error_handler(array($this, 'handleImageError'), E_ALL | E_STRICT ) ;
			$fileInfo = $api->callApi('filedata', 'fetchImageByFiledataid', $request);
		}
		else if (!empty($serverData['photoid']) AND intval($serverData['photoid']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['photoid']);
			$fileInfo = $api->callApi('content_photo', 'fetchImageByPhotoid', $request);
		}
		else if (!empty($serverData['linkid']) AND intval($serverData['linkid']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['linkid']);
			$request['includeData'] = false;
			set_error_handler(array($this, 'handleImageError'), E_ALL | E_STRICT ) ;
			$fileInfo = $api->callApi('content_link', 'fetchImageByLinkId', $request);
		}
		else if (!empty($serverData['attachid']) AND intval($serverData['attachid']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['attachid']);
			set_error_handler(array($this, 'handleImageError'), E_ALL | E_STRICT ) ;
			$fileInfo = $api->callApi('content_attach', 'fetchImage', $request);
		}
		else if (!empty($serverData['channelid']) AND intval($serverData['channelid']))
		{
			// Don't put an intval() call in an if condition and then subsequently
			// *use* the non-intval'ed value. Normally, you'd use intval to
			// typecast *before* the if condition.
			$request['id'] = intval($serverData['channelid']);
			set_error_handler(array($this, 'handleImageError'), E_ALL | E_STRICT ) ;
			$fileInfo = $api->callApi('content_channel', 'fetchChannelIcon', $request);
		}

		if (!empty($fileInfo['filedata']))
		{
			header('ETag: "' . $fileInfo['filedataid'] . '"');
			header('Accept-Ranges: bytes');
			header('Content-transfer-encoding: binary');
			header("Content-Length: " . $fileInfo['filesize'] );

			$fileInfo['extension'] = strtolower($fileInfo['extension']);
			if (in_array($fileInfo['extension'], array('jpg', 'jpe', 'jpeg', 'gif', 'png')))
			{
				header("Content-Disposition: inline; filename=\"image_" . $fileInfo['filedataid'] .  "." . $fileInfo['extension'] . "\"");
				header('Content-transfer-encoding: binary');
			}
			else
			{
				$attachInfo = $api->callApi('content_attach', 'fetchAttachByFiledataids', array('filedataids' => array($fileInfo['filedataid'])));

				// force files to be downloaded because of a possible XSS issue in IE
				header("Content-disposition: attachment; filename=\"" . $attachInfo[$fileInfo['filedataid']]['filename']. "\"");
			}
			header('Cache-control: max-age=31536000, private');
			header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
			header('Pragma:');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileInfo['dateline']) . ' GMT');
			foreach ($fileInfo['headers'] as $header)
			{
				header($header);
			}

			echo $fileInfo['filedata'];
		}
		else
		{
			$this->invalidFileResult($api);
		}
	}

	private function invalidFileResult($api)
	{
		$apiresult = $api->callApi('phrase', 'renderPhrases', array(array('invalid_file_specified' => 'invalid_file_specified')));
		if (!isset($apiresult['errors']))
		{
			$error = $apiresult['phrases']['invalid_file_specified'];
		}

		header("Content-Type: text/plain");
		header("Content-Length: " . sizeof($error));
		http_response_code(404);
		echo $error;
		exit;
	}

	/**
	 * If there is an error, there's little we can do. We have a 1px file. Let's return that with a header so the
	 * client won't request it again soon;
	 */
	public function handleImageError($errno)
	{
		$errorReporting = error_reporting();

		if ($errorReporting === 0)
		{
			/*
				VBV-15630

				If error reporting is 0, error reporting is suppressed by @ (or turned off entirely), so we shouldn't
				override that.
			*/
			switch($errno)
			{
				case E_WARNING:
				case E_NOTICE:
					// The only issue I was able to observe while debugging VBV-15630 was misc warnings & notices ...
					return;
				default:
					// ... but let's just return for *all* errors to make tihs consistent with @ behavior.
					return;
			}
		}
		$api = Api_InterfaceAbstract::instance('light');
		$this->invalidFileResult($api);
	}

	/**
	 * Displays a vB page for exceptions
	 *
	 *	@param	mixed 	exception
	 *	@param	bool 	Bypass API and display simple error message
	 */
	public static function handleException($exception, $simple = false)
	{
		$config = vB5_Config::instance();

		if ($config->debug)
		{
			echo "Exception ". $exception->getMessage() . ' in file ' . $exception->getFile() . ", line " . $exception->getLine() .
				"<br />\n". $exception->getTrace();
		}

		if (!headers_sent())
		{
			// Set HTTP Headers
			if ($exception instanceof vB5_Exception_404)
			{
				http_response_code(404);
			}
			else
			{
				http_response_code(500);
			}
		}
		die();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 95376 $
|| #######################################################################
\*=========================================================================*/
