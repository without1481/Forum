<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

// ##################### DEFINE IMPORTANT CONSTANTS #######################
define('CVS_REVISION', '$RCSfile$ - $Revision: 92140 $');
define('NOZIP', 1);

// #################### PRE-CACHE TEMPLATES AND DATA ######################
global $phrasegroups, $specialtemplates, $vbphrase;

$phrasegroups = array('thread', 'threadmanage', 'prefix');
$specialtemplates = array();

// ########################## REQUIRE BACK-END ############################
require_once(dirname(__FILE__) . '/global.php');
require_once(DIR . '/includes/functions_databuild.php');
require_once(DIR . '/includes/adminfunctions_prefix.php');

@set_time_limit(0);

// ######################## CHECK ADMIN PERMISSIONS #######################
if (!can_administer('canadminthreads'))
{
	print_cp_no_permission();
}

$vbulletin->input->clean_array_gpc('r', array(
	'channelid' => vB_Cleaner::TYPE_INT,
	'pollid'  => vB_Cleaner::TYPE_INT,
));

// ############################# LOG ACTION ###############################

$log = '';
if(!empty($vbulletin->GPC['channelid']))
{
	$log = "channel id = " . $vbulletin->GPC['channelid'];
}
else if (!empty($vbulletin->GPC['pollid']))
{
	$log = "poll id = " . $vbulletin->GPC['pollid'];
}
log_admin_action($log);

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################

//not all of the original thread tools ported.  The remaining functionality
//can be found in the vb4 thread.php admincp file.

// ###################### Start Prune by user #######################
if ($_REQUEST['do'] == 'pruneuser')
{
	$vbulletin->input->clean_array_gpc('r', array(
		'username'  => vB_Cleaner::TYPE_NOHTML,
		'channelid'   => vB_Cleaner::TYPE_INT,
		'subforums' => vB_Cleaner::TYPE_BOOL,
		'userid'    => vB_Cleaner::TYPE_UINT
	));


	// we only ever submit this via post
	$vbulletin->input->clean_array_gpc('p', array(
		'confirm'   => vB_Cleaner::TYPE_BOOL,
	));

	print_cp_header($vbphrase['topic_manager_admincp']);
	$assertor = vB::getDbAssertor();
	$nodeApi = vB_Api::instance('node');

	if (empty($vbulletin->GPC['username']) AND !$vbulletin->GPC['userid'])
	{
		print_stop_message2('invalid_user_specified');
	}
	else if (!$vbulletin->GPC['channelid'])
	{
		print_stop_message('invalid_channel_specified');
	}

	if ($vbulletin->GPC['channelid'] == -1)
	{
		$forumtitle = $vbphrase['all_forums'];
	}
	else
	{
		$channel = $nodeApi->getNode($vbulletin->GPC['channelid']);
		$forumtitle = $channel['title'] . ($vbulletin->GPC['subforums'] ? ' (' . $vbphrase['include_child_channels'] . ')' : '');
	}

	$conditions = array();
	if ($vbulletin->GPC['username'])
	{
		$conditions[] = array('field' => 'username', 'value' => $vbulletin->GPC['username'], 'operator' => vB_dB_Query::OPERATOR_INCLUDES);
	}
	else
	{
		$conditions['userid'] = $vbulletin->GPC['userid'];
	}

	$result = $assertor->select('user', $conditions, 'username', array('userid', 'username'));

	if (!$result->valid())
	{
		print_stop_message2('invalid_user_specified');
	}
	else
	{
		echo '<p>' . construct_phrase($vbphrase['about_to_delete_posts_in_forum_x_by_users'], $forumtitle) . '</p>';

		$filter = array(
			'channelid' => $vbulletin->GPC['channelid'],
			'subforums' =>  $vbulletin->GPC['subforums'],
		);

		foreach ($result AS $user)
		{
			$filter['userid'] = $user['userid'];

			$params = fetch_thread_move_prune_sql($assertor, $filter);
			$params['special']['includeposts'] = true;
			$hiddenParams = sign_client_string(serialize($params));

			print_form_header('admincp/nodetools', 'donodesall');
			print_table_header(construct_phrase($vbphrase['prune_all_x_posts_automatically'], $user['username']), 2, 0);
			construct_hidden_code('type', 'prune');
			construct_hidden_code('criteria', $hiddenParams);
			print_submit_row(construct_phrase($vbphrase['prune_all_x_posts_automatically'], $user['username']), '', 2);

			print_form_header('admincp/nodetools', 'donodessel');
			print_table_header(construct_phrase($vbphrase['prune_x_posts_selectively'], $user['username']), 2, 0);
			construct_hidden_code('type', 'prune');
			construct_hidden_code('criteria', $hiddenParams);
			print_submit_row(construct_phrase($vbphrase['prune_x_posts_selectively'], $user['username']), '', 2);
		}
	}
}


// ###################### Start Prune #######################
if ($_REQUEST['do'] == 'prune')
{
	print_cp_header($vbphrase['topic_manager_admincp']);

	//print_form_header('', '');
	print_form_header('admincp/nodetools', 'donodes');
	print_table_header($vbphrase['prune_topics_manager']);
	print_description_row($vbphrase['pruning_many_threads_is_a_server_intensive_process']);

	construct_hidden_code('type', 'prune');
	print_move_prune_rows();
	print_submit_row($vbphrase['prune_topics']);

	print_form_header('admincp/nodetools', 'pruneuser');
	print_table_header($vbphrase['prune_by_username']);
	print_input_row($vbphrase['username'], 'username');
	print_channel_chooser($vbphrase['channel'], 'channelid', -1, $vbphrase['all_channels'], true, false, null, true);

	print_yes_no_row($vbphrase['include_child_channels'], 'subforums');
	print_submit_row($vbphrase['prune_topics']);
}

/************ GENERAL MOVE/PRUNE HANDLING CODE ******************/

// ###################### Start makeprunemoveboxes #######################
function print_move_prune_rows()
{
	global $vbphrase;
	$nolimitdfn_0 = '<dfn>' . construct_phrase($vbphrase['note_leave_x_specify_no_limit'], '0') . '</dfn>';
	$nolimitdfn_neg1 = '<dfn>' . construct_phrase($vbphrase['note_leave_x_specify_no_limit'], '-1') . '</dfn>';

	print_description_row($vbphrase['date_options'], 0, 2, 'thead', 'center');
	print_input_row($vbphrase['original_post_date_is_at_least_xx_days_ago'], 'topic[originaldaysolder]', 0, 1, 5);
	print_input_row($vbphrase['original_post_date_is_at_most_xx_days_ago'] . $nolimitdfn_0, 'topic[originaldaysnewer]', 0, 1, 5);
	print_input_row($vbphrase['last_post_date_is_at_least_xx_days_ago'], 'topic[lastdaysolder]', 0, 1, 5);
	print_input_row($vbphrase['last_post_date_is_at_most_xx_days_ago'] . $nolimitdfn_0, 'topic[lastdaysnewer]', 0, 1, 5);

	print_description_row($vbphrase['view_options'], 0, 2, 'thead', 'center');
	print_input_row($vbphrase['topic_has_at_least_xx_replies'], 'topic[repliesleast]', 0, 1, 5);
	print_input_row($vbphrase['topic_has_at_most_xx_replies'] . $nolimitdfn_neg1, 'topic[repliesmost]', -1, 1, 5);
	print_input_row($vbphrase['topic_has_at_least_xx_views'], 'topic[viewsleast]', 0, 1, 5);
	print_input_row($vbphrase['topic_has_at_most_xx_views'] . $nolimitdfn_neg1, 'topic[viewsmost]', -1, 1, 5);

	print_description_row($vbphrase['status_options'], 0, 2, 'thead', 'center');
	print_yes_no_other_row($vbphrase['topic_is_sticky'], 'topic[issticky]', $vbphrase['either'], 0);

	print_yes_no_other_row($vbphrase['topic_is_unpublished'], 'topic[unpublished]', $vbphrase['either'], -1);
	print_yes_no_other_row($vbphrase['topic_is_awaiting_moderation'], 'topic[moderated]', $vbphrase['either'], -1);

	print_yes_no_other_row($vbphrase['topic_is_open'], 'topic[isopen]', $vbphrase['either'], -1);
	print_yes_no_other_row($vbphrase['topic_is_redirect'], 'topic[isredirect]', $vbphrase['either'], 0);

	print_description_row($vbphrase['other_options'], 0, 2, 'thead', 'center');
	print_input_row($vbphrase['username'], 'topic[posteduser]');
	print_input_row($vbphrase['title'], 'topic[titlecontains]');
	print_channel_chooser($vbphrase['channel'], 'topic[channelid]', -1, $vbphrase['all_channels'], true, false, null, true);
	print_yes_no_row($vbphrase['include_child_channels'], 'topic[subforums]');

	if ($prefix_options = construct_prefix_options(0, '', true, true))
	{
		print_label_row($vbphrase['prefix'], '<select name="topic[prefixid]" class="bginput">' . $prefix_options . '</select>', '', 'top', 'prefixid');
	}
}

// ###################### Start genmoveprunequery #######################
function fetch_thread_move_prune_sql($db, $topic)
{
	$conditions = array();
	$channelinfo = array();
	$special = array();

	$timenow = vB::getRequest()->getTimeNow();

	//probably not needed because we'll have a starter check by default.  But we don't want
	//channels here regardless.
	$type = vB_Types::instance()->getContentTypeId('vBForum_Channel');
	$conditions[] = array('field' => 'node.contenttypeid', 'value' => $type, 'operator' => vB_dB_Query::OPERATOR_NE);

	// original post
	if (isset($topic['originaldaysolder']) AND intval($topic['originaldaysolder']))
	{
		$timecut = $timenow - ($topic['originaldaysolder'] * 86400);
		$conditions[] = array('field' => 'node.created', 'value' => $timecut, 'operator' => vB_dB_Query::OPERATOR_LTE);
	}

	if (isset($topic['originaldaysnewer']) AND intval($topic['originaldaysnewer']))
	{
		$timecut = $timenow - ($topic['originaldaysnewer'] * 86400);
		$conditions[] = array('field' => 'node.created', 'value' => $timecut, 'operator' => vB_dB_Query::OPERATOR_GTE);
	}

	// last post
	if (isset($topic['lastdaysolder']) AND intval($topic['lastdaysolder']))
	{
		$timecut = $timenow - ($topic['lastdaysolder'] * 86400);
		$conditions[] = array('field' => 'node.lastupdate', 'value' => $timecut, 'operator' => vB_dB_Query::OPERATOR_LTE);
	}

	if (isset($topic['lastdaysnewer']) AND intval($topic['lastdaysnewer']))
	{
		$timecut = $timenow - ($topic['lastdaysnewer'] * 86400);
		$conditions[] = array('field' => 'node.lastupdate', 'value' => $timecut, 'operator' => vB_dB_Query::OPERATOR_GTE);
	}

	// replies
	if (isset($topic['repliesleast']) AND intval($topic['repliesleast']) > 0)
	{
		$conditions[] = array('field' => 'node.textcount', 'value' => intval($topic['repliesleast']), 'operator' => vB_dB_Query::OPERATOR_GTE);
	}

	if (isset($topic['repliesmost']) AND intval($topic['repliesmost']) > -1)
	{
		$conditions[] = array('field' => 'node.textcount', 'value' => intval($topic['repliesmost']), 'operator' => vB_dB_Query::OPERATOR_LTE);
	}

	// views
	if (isset($topic['viewsleast']) AND intval($topic['viewsleast']) > 0)
	{
		$conditions[] = array('field' => 'nodeview.count', 'value' => intval($topic['viewsleast']), 'operator' => vB_dB_Query::OPERATOR_GTE);
	}

	if (isset($topic['viewsmost']) AND intval($topic['viewsmost']) > -1)
	{
		$conditions[] = array('field' => 'nodeview.count', 'value' => intval($topic['viewsmost']), 'operator' => vB_dB_Query::OPERATOR_LTE);
	}

	// sticky
	if (isset($topic['issticky']) AND $topic['issticky'] != -1)
	{
		$conditions['node.sticky'] = $topic['issticky'];
	}

	if (isset($topic['unpublished']) AND $topic['unpublished'] != -1)
	{
		if ($topic['unpublished'])
		{
			//this can't be handled with standard conditions
			$special['unpublished'] = 'yes';
			$special['timenow'] = $timenow;
		}
		else
		{
			$special['unpublished'] = 'no';
			$special['timenow'] = $timenow;
		}
	}

	if (isset($topic['moderated']) AND $topic['moderated'] != -1)
	{
		$conditions['node.approved'] = !$topic['moderated'];
	}

	//status
	if (isset($topic['isopen']) AND $topic['isopen'] != -1)
	{
		$conditions['node.open'] = $topic['isopen'];
	}

	if (isset($topic['isredirect']) AND $topic['isredirect'] != -1)
	{
		$op = (($topic['isredirect'] == 1) ? vB_dB_Query::OPERATOR_EQ : vB_dB_Query::OPERATOR_NE);
		$type = vB_Types::instance()->getContentTypeId('vBForum_Redirect');

		$conditions[] = array('field' => 'node.contenttypeid', 'value' => $type, 'operator' => $op);
	}

	// posted by
	if (isset($topic['posteduser']) AND $topic['posteduser'])
	{
		$user = $db->getRow('user', array('username' => vB_String::htmlSpecialCharsUni($topic['posteduser'])));
		if (!$user)
		{
			print_stop_message('invalid_username_specified');
		}

		$conditions['node.userid'] = $user['userid'];
	}

	else if (isset($topic['userid']) AND $topic['userid'])
	{
		$conditions['node.userid'] = $topic['userid'];
	}

	// title contains
	if (isset($topic['titlecontains']) AND $topic['titlecontains'])
	{
		//we are still encoding the title in the DB so we need to do the same to the
		//string in order to get it to match.  This will likely prove fragile but not doing doesn't work.
		$contains = vB_String::htmlSpecialCharsUni($topic['titlecontains']);
		$conditions[] = array('field' => 'node.title', 'value' => $contains, 'operator' => vB_dB_Query::OPERATOR_INCLUDES);
	}

	// forum
	$topic['channelid'] = intval($topic['channelid']);

	if ($topic['channelid'] != -1)
	{
		$channelinfo['channelid'] = $topic['channelid'];
		$channelinfo['subforums'] = $topic['subforums'];
	}

	// prefixid
	if (isset($topic['prefixid']) AND $topic['prefixid'] != '')
	{
		$conditions['node.prefixid'] = ($topic['prefixid'] == '-1' ? '' : $topic['prefixid']);
	}

	$channelApi = vB_Api::instance('content_channel');
	$channels = $channelApi->fetchTopLevelChannelIds();
	if(isset($channels['errors']))
	{
		print_stop_message_array($channels['errors']);
	}

	$special['specialchannelid'] = $channels['special'];
	return array('conditions' => $conditions, 'channelinfo' => $channelinfo, 'special'=> $special);
}

// ###################### Start thread move/prune by options #######################
if ($_POST['do'] == 'donodes')
{
	$vbulletin->input->clean_array_gpc('p', array(
		'type'        => vB_Cleaner::TYPE_NOHTML,
		'topic'      => vB_Cleaner::TYPE_ARRAY,
		'destforumid' => vB_Cleaner::TYPE_INT,
	));

	print_cp_header($vbphrase['topic_manager_admincp']);

	$topic = $vbulletin->GPC['topic'];

	if ($topic['channelid'] == 0)
	{
		print_stop_message('please_complete_required_fields');
	}

/*
	if ($vbulletin->GPC['type'] == 'move')
	{
		$foruminfo = fetch_foruminfo($vbulletin->GPC['destforumid']);
		if (!$foruminfo)
		{
			print_stop_message('invalid_destination_forum_specified');
		}
		if (!$foruminfo['cancontainthreads'] OR $foruminfo['link'])
		{
			print_stop_message('destination_forum_cant_contain_threads');
		}
	}
 */

	$assertor = vB::getDbAssertor();

	$params = fetch_thread_move_prune_sql($assertor, $vbulletin->GPC['topic']);
	$hiddenParams = sign_client_string(serialize($params));

	$count = $assertor->getRow('vBForum:getThreadPruneCount', $params);

	if (!$count['count'])
	{
		print_stop_message('no_topics_matched_your_query');
	}

	print_form_header('admincp\nodetools', 'donodesall');
	construct_hidden_code('type', $vbulletin->GPC['type']);
	construct_hidden_code('criteria', $hiddenParams);

	print_table_header(construct_phrase($vbphrase['x_topic_matches_found'], $count['count']));
	if ($vbulletin->GPC['type'] == 'prune')
	{
		print_submit_row($vbphrase['prune_all_topics'], '');
	}
/*
	else
	{
		construct_hidden_code('destforumid', $vbulletin->GPC['destforumid']);
		print_submit_row($vbphrase['move_all_threads'], '');
	}
 */

	print_form_header('admincp\nodetools', 'donodessel');
	construct_hidden_code('type', $vbulletin->GPC['type']);
	construct_hidden_code('criteria', $hiddenParams);
	print_table_header(construct_phrase($vbphrase['x_topic_matches_found'], $count['count']));
	if ($vbulletin->GPC['type'] == 'prune')
	{
		print_submit_row($vbphrase['prune_topics_selectively'], '');
	}
/*
	else
	{
		construct_hidden_code('destforumid', $vbulletin->GPC['destforumid']);
		print_submit_row($vbphrase['move_topics_selectively'], '');
	}
 */
}

// ###################### Start move/prune all matching #######################
if ($_POST['do'] == 'donodesall')
{
	require_once(DIR . '/includes/functions_log_error.php');

	$vbulletin->input->clean_array_gpc('p', array(
		'type'        => vB_Cleaner::TYPE_NOHTML,
		'criteria'    => vB_Cleaner::TYPE_STR,
		'destforumid' => vB_Cleaner::TYPE_INT,
	));

	print_cp_header($vbphrase['topic_manager_admincp']);

	$assertor = vB::getDbAssertor();
	$params = unserialize(verify_client_string($vbulletin->GPC['criteria']));

	$nodeids = array();

	if($params)
	{
		$set = $assertor->assertQuery('vBForum:getThreadPrune', $params);
		foreach($set AS $row)
		{
			$nodeids[] = $row['nodeid'];
		}
	}

	$nodeApi = vB_Api::instance('node');

	if ($vbulletin->GPC['type'] == 'prune')
	{
		echo '<p><b>' . $vbphrase['deleting_topics'] . '</b>';

		$result = $nodeApi->deleteNodes($nodeids, true);
		if(isset($result['errors']))
		{
			print_stop_message_array($result['errors']);
		}
		echo ' ' . $vbphrase['done'] . '</p>';

		print_stop_message2('pruned_topics_successfully', 'admincp/nodetools', array('do' => 'prune'));
	}
/*
	else if ($vbulletin->GPC['type'] == 'move')
	{
		$threadslist = '0';
		while ($thread = $db->fetch_array($threads))
		{
			$threadslist .= ",$thread[threadid]";
		}

		$db->query_write("
			UPDATE " . TABLE_PREFIX . "thread SET
				forumid = " . $vbulletin->GPC['destforumid'] . "
			WHERE threadid IN ($threadslist)
		");

		$vbulletin->db->query_write("TRUNCATE TABLE " . TABLE_PREFIX . "postparsed");

		require_once(DIR . '/includes/functions_prefix.php');
		remove_invalid_prefixes($threadslist, $vbulletin->GPC['destforumid']);

		require_once(DIR . '/includes/functions_databuild.php');
		build_forum_counters($vbulletin->GPC['destforumid']);

		//define('CP_REDIRECT', 'thread.php?do=move');
		define('CP_BACKURL', '');
		print_stop_message('moved_threads_successfully');
	}
 */
}

// ###################### Start move/prune select #######################
if ($_POST['do'] == 'donodessel')
{

	$vbulletin->input->clean_array_gpc('p', array(
		'type'        => vB_Cleaner::TYPE_NOHTML,
		'criteria'    => vB_Cleaner::TYPE_STR,
		'destforumid' => vB_Cleaner::TYPE_INT,
	));

	print_cp_header($vbphrase['topic_manager_admincp']);

	$assertor = vB::getDbAssertor();
	$params = unserialize(verify_client_string($vbulletin->GPC['criteria']));

	$nodeids = array();

	if($params)
	{
		$set = $assertor->assertQuery('vBForum:getThreadPrune', $params);
		foreach($set AS $row)
		{
			$nodeids[] = $row['nodeid'];
		}
	}

	$nodeApi = vB_Api::instance('node');

	$nodes = $nodeApi->getNodes($nodeids);
	if(isset($nodes['errors']))
	{
		print_stop_message_array($nodes['errors']);
	}

	$topicsOnly = true;
	$starterTitles = array();
	$needTitles = array();
	foreach($nodes AS $node)
	{
		if($node['starter'] == $node['nodeid'])
		{
			$starterTitles[$node['nodeid']] = $node['title'];
		}
		else
		{
			$topicsOnly = false;
			if (!isset($starterTitles[$node['starter']]))
			{
				$needTitles[] = $node['starter'];
			}
		}
	}

	$needTitles = array_unique($needTitles);

	$starters = $nodeApi->getNodes($needTitles);
	foreach($starters AS $starter)
	{
		$starterTitles[$starter['nodeid']] = $starter['title'];
	}

	unset($staters);

	print_form_header('admincp/nodetools', 'donodesselfinish');
	construct_hidden_code('type', $vbulletin->GPC['type']);
	construct_hidden_code('destforumid', $vbulletin->GPC['destforumid']);
	if ($vbulletin->GPC['type'] == 'prune')
	{
		print_table_header($vbphrase[($topicsOnly ? 'prune_topics_selectively' : 'prune_nodes_selectively')], 5);
	}
/*
	else if ($vbulletin->GPC['type'] == 'move')
	{
		print_table_header($vbphrase['move_threads_selectively'], 5);
	}
 */
	$cells = array(
		'<input type="checkbox" name="allbox" title="' . $vbphrase['check_all'] . '" onclick="js_check_all(this.form);" checked="checked" />',
		$vbphrase['title'],
		$vbphrase['user'],
		$vbphrase['replies'],
		$vbphrase['last_post'],
	);

	print_cells_row($cells, true, false, 0, 'top', false, false, false, array(1 => 'left'));

	foreach($nodes AS $node)
	{
		$prefix = '';
		if($node['prefixid'])
		{
			$prefix = '[' . vB_String::htmlSpecialCharsUni($vbphrase["prefix_$node[prefixid]_title_plain"]) . '] ';
		}

		if ($node['starter'] == $node['nodeid'])
		{
			$title = $node['title'];
			$nodeUrl = vB5_Route::buildUrl($node['routeid'] . '|fullurl', $node);
		}
		else
		{
			$title = construct_phrase($vbphrase['child_of_x'], $starterTitles[$node['starter']]) . ' (nodeid ' .  $node['nodeid'] . ')';
			$nodeUrl=	vB5_Route::buildUrl($node['routeid'] . '|fullurl',
				array(
					'nodeid' => $node['starter'],
					'innerPost' => $node['nodeid'],
					'innerPostParent' => $node['parentid'],
				)
			);
		}

		$cells = array();
		$cells[] = "<input type=\"checkbox\" name=\"nodes[$node[nodeid]]\" tabindex=\"1\" checked=\"checked\" />";
		$cells[] = $prefix . '<a href="' . $nodeUrl. '" target="_blank">' . $title . '</a>';

		if ($node['userid'])
		{
			$authorUrl = vB5_Route::buildUrl('profile|fullurl', $node);
			$cells[] = '<span class="smallfont"><a href="' . $authorUrl . '" target="_blank">' . $node['authorname'] . '</a></span>';
		}
		else
		{
			$cells[] = '<span class="smallfont">' . $node['authorname'] . '</span>';
		}

		$cells[] = "<span class=\"smallfont\">$node[textcount]</span>";
		$cells[] = '<span class="smallfont">' . vbdate($vbulletin->options['dateformat'] . ' ' . $vbulletin->options['timeformat'], $node['lastcontent']) . '</span>';

		print_cells_row($cells, false, false, 0, 'top', false, false, false, array(1 => 'left'));
	}
	print_submit_row($vbphrase['go'], NULL, 5);
}

// ###################### Start move/prune select - finish! #######################
if ($_POST['do'] == 'donodesselfinish')
{

	require_once(DIR . '/includes/functions_log_error.php');

	$vbulletin->input->clean_array_gpc('p', array(
		'type'        => vB_Cleaner::TYPE_NOHTML,
		'nodes'      => vB_Cleaner::TYPE_ARRAY_BOOL,
		'destforumid' => vB_Cleaner::TYPE_INT,
	));

	print_cp_header($vbphrase['topic_manager_admincp']);

	if(is_array($vbulletin->GPC['nodes']))
	{
		$nodeids = array_keys($vbulletin->GPC['nodes']);
	}

	if (!empty($nodeids))
	{
		if ($vbulletin->GPC['type'] == 'prune')
		{
			echo '<p><b>' . $vbphrase['deleting_threads'] . '</b>';

			$nodeApi = vB_Api::instance('node');
			$result = $nodeApi->deleteNodes($nodeids, true);
			if(isset($result['errors']))
			{
				print_stop_message_array($result['errors']);
			}

			print_stop_message2('pruned_topics_successfully', 'admincp/nodetools', array('do' => 'prune'));
		}
/*
		else if ($vbulletin->GPC['type'] == 'move')
		{
			$threadslist = '0';
			foreach ($thread AS $threadid)
			{
				$threadslist .= ', ' . intval($threadid);
			}

			$db->query_write("
				UPDATE " . TABLE_PREFIX . "thread SET
					forumid = " . $vbulletin->GPC['destforumid'] . "
				WHERE threadid IN ($threadslist)
			");

			$vbulletin->db->query_write("TRUNCATE TABLE " . TABLE_PREFIX . "postparsed");

			require_once(DIR . '/includes/functions_prefix.php');
			remove_invalid_prefixes($threadslist, $vbulletin->GPC['destforumid']);

			require_once(DIR . '/includes/functions_databuild.php');
			build_forum_counters($vbulletin->GPC['destforumid']);

			//define('CP_REDIRECT', 'thread.php?do=move');
			define('CP_BACKURL', '');
			print_stop_message('moved_threads_successfully');
		}
 */
	}
	else
	{
		print_stop_message2('please_select_at_least_one_node');
	}
}

print_cp_footer();

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/
?>
