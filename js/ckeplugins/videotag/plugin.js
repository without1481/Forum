/*!=======================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

CKEDITOR.plugins.add('videotag', {
	requires: 'dialog',
	init: function( editor ) {
		editor.addCommand( 'videotag', new CKEDITOR.dialogCommand( 'videotag' ) );
		editor.ui.addButton && editor.ui.addButton( 'Video', {
			label: vBulletin.phrase.get('insert_video'),
			command:  'videotag',
			icon: window.pageData.baseurl + '/core/images/editor/video.png'
		});
		CKEDITOR.dialog.add( 'videotag', vBulletin.ckeditor.config.pluginPath + 'videotag/dialogs/videotag.js' );
	}
});

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
