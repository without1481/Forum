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

error_reporting(E_ALL & ~E_NOTICE);

// ###################### Start print_statistic_result #######################
function print_statistic_result($date, $bar, $value, $width)
{
	$bgclass = fetch_row_bgclass();

	$style = 'width:' . $width . '%; ' .
		'height: 23px; ' .
		'border:' . vB_Template_Runtime::fetchStyleVar('poll_result_border') . '; ' .
		'background:' . vB_Template_Runtime::fetchStyleVar('poll_result_color_' . str_pad(strval(intval($bar)), 2, '0', STR_PAD_LEFT)) . '; ';

	echo '<tr><td width="0" class="' . $bgclass . '">' . $date . "</td>\n";
	echo '<td width="100%" class="' . $bgclass . '" nowrap="nowrap"><div style="' . $style . '">&nbsp;</div></td>' . "\n";
	echo '<td width="0%" class="' . $bgclass . '" nowrap="nowrap">' . $value . "</td></tr>\n";
}

// ###################### Start print_statistic_code #######################
function print_statistic_code($title, $name, $start, $end, $nullvalue = true, $scope = 'daily', $sort = 'date_desc', $script = 'stats')
{

	global $vbphrase;

	print_form_header("admincp/$script", $name);
	print_table_header($title);

	print_time_row($vbphrase['start_date'], 'start', $start, false);
	print_time_row($vbphrase['end_date'], 'end', $end, false);

	if ($name != 'activity')
	{
		print_select_row($vbphrase['scope'], 'scope', array('daily' => $vbphrase['daily'], 'weekly' => $vbphrase['weekly_gstats'], 'monthly' => $vbphrase['monthly']), $scope);
	}
	else
	{
		construct_hidden_code('scope', 'daily');
	}
	print_select_row($vbphrase['order_by_gcpglobal'], 'sort', array(
		'date_asc'   => $vbphrase['date_ascending'],
		'date_desc'  => $vbphrase['date_descending'],
		'total_asc'  => $vbphrase['total_ascending'],
		'total_desc' => $vbphrase['total_descending'],
	), $sort);
	print_yes_no_row($vbphrase['include_empty_results'], 'nullvalue', $nullvalue);
	print_submit_row($vbphrase['go']);
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93305 $
|| #######################################################################
\*=========================================================================*/
