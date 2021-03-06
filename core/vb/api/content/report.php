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
 * vB_Api_Content_Report
 *
 * @package vBApi
 * @author xiaoyu
 * @copyright Copyright (c) 2011
 * @version $Id: report.php 95123 2017-08-28 23:01:48Z ksours $
 * @access public
 */
class vB_Api_Content_Report extends vB_Api_Content_Text
{
	//override in client- the text name
	protected $contenttype = 'vBForum_Report';

	//The table for the type-specific data.
	protected $tablename = array('report', 'text');

	protected $reportChannel;

	/**
	 * Constructor, no external instantiation.
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->library = vB_Library::instance('Content_Report');
		$this->reportChannel = $this->nodeApi->fetchReportChannel();
	}

	/**
	 * Adds a new node.
	 *
	 * @param  mixed            $data Array of field => value pairs which define the record.
	 * @param  array            Array of options for the content being created.
	 *                          Understands skipTransaction, skipFloodCheck, floodchecktime, skipDupCheck, skipNotification, nl2br, autoparselinks.
	 *                          - nl2br: if TRUE, all \n will be converted to <br /> so that it's not removed by the html parser (e.g. comments).
	 *                          - wysiwyg: if true convert html to bbcode.  Defaults to true if not given.
	 *
	 * @throws vB_Exception_Api
	 *
	 * @return integer          the new nodeid
	 */
	public function add($data, $options = array())
	{
		if (!$this->library->validate($data, vB_Library_Content::ACTION_ADD))
		{
			throw new vB_Exception_Api('no_create_permissions');
		}

		$vboptions = vB::getDatastore()->getValue('options');
		if(!empty($data['pagetext']))
		{
			$strlen = vB_String::vbStrlen($this->library->parseAndStrip($data['pagetext']), true);
			if ($strlen < $vboptions['postminchars'])
			{
				throw new vB_Exception_Api('please_enter_message_x_chars', $vboptions['postminchars']);
			}
			if($vboptions['postmaxchars'] != 0 AND $strlen > $vboptions['postmaxchars'])
			{
				throw new vB_Exception_Api('maxchars_exceeded_x_y', array($vboptions['postmaxchars'], $strlen));
			}
		}
		else if(!empty($data['rawtext']))
		{

			$strlen = vB_String::vbStrlen($this->library->parseAndStrip($data['rawtext']), true);
			if ($strlen < $vboptions['postminchars'])
			{
				throw new vB_Exception_Api('please_enter_message_x_chars', $vboptions['postminchars']);
			}
			if($vboptions['postmaxchars'] != 0 AND $strlen > $vboptions['postmaxchars'])
			{
				throw new vB_Exception_Api('maxchars_exceeded_x_y', array($vboptions['postmaxchars'], $strlen));
			}
		}
		else
		{
			throw new vB_Exception_Api('invalid_data');
		}

		//this will be set by the library function as well, but cleanInput expects this to
		//exist in the input data and it not being set at this point will cause problems.
		$data['parentid'] = $this->reportChannel;
		$data = $this->cleanInput($data);
		$this->cleanOptions($options);

		$wysiwyg = true;
		if(isset($options['wysiwyg']))
		{
			$wysiwyg = (bool) $options['wysiwyg'];
		}

		$result = $this->library->add($data, $options, $wysiwyg);
		return $result['nodeid'];
	}

	/**
	 * Report is not allowed to be updated.
	 *
	 * @throws vB_Exception_Api
	 *
	 * @param  $nodeid
	 * @param  $data
	 *
	 * @return void
	 */
	public function update($nodeid, $data)
	{
		throw new vB_Exception_Api('not_implemented');
	}

	/**
	 * Opens or closes reports
	 *
	 * @param  array  $nodeids Array of node IDs
	 * @param  string $op 'open' or 'close'
	 *
	 * @return standard success array
	 */
	public function openClose($nodeids, $op)
	{
		$data = array();

		// We need to check the permissions of the nodeids that these reports apply to, not the report
		$reportNodeids = $this->getReportNodes($nodeids);
		if (!vB::getUserContext()->isModerator() OR !$this->library->validate($data, vB_Library_Content::ACTION_UPDATE, $reportNodeids))
		{
			throw new vB_Exception_Api('no_permission');
		}

		$this->library->openClose($nodeids, $op);
		return array('success' => true);
	}

	/**
	 * Deletes one or more reports
	 *
	 * @throws vB_Exception_Api
	 *
	 * @param  $nodeids
	 *
	 * @return void
	 */
	public function bulkdelete($nodeids)
	{
		$data = array();

		// We need to check the permissions of the nodeids that these reports apply to, not the report
		$reportNodeids = $this->getReportNodes($nodeids);
		if (!vB::getUserContext()->isModerator() OR !$this->library->validate($data, vB_Library_Content::ACTION_UPDATE, $reportNodeids))
		{
			throw new vB_Exception_Api('no_permission');
		}

		return $this->library->bulkdelete($nodeids);
	}

	/**
	 * Converts report nodes to associated nodes
	 *
	 * @param  array $nodeids
	 *
	 * @return array $nodeids
	 */
	protected function getReportNodes($nodeids)
	{
		$nodes = array();
		$results = vB::getDbAssertor()->getRows('vBForum:report', array(
			'nodeid' => $nodeids,
		));
		foreach ($results AS $node)
		{
			$nodes[] = $node['reportnodeid'];
		}

		return $nodes;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 95123 $
|| #######################################################################
\*=========================================================================*/
