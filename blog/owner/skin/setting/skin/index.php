<?php
/// Copyright (c) 2004-2006, Tatter & Company / Tatter & Friends.
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('ROOT', '../../../../..');
$IV = array(
	'POST' => array(
		'entriesOnPage' => array('int'),
		'entriesOnList' => array('int'),
		'entriesOnRecent' => array('int'),
		'commentsOnRecent' => array('int'),
		'commentsOnGuestbook' => array('int'),
		'archivesOnPage' => array('int'),
		'tagboxAlign' => array('int'),
		'tagsOnTagbox' => array('int'),
		'trackbacksOnRecent' => array('int'),
		'showListOnCategory' => array('int'),
		'showListOnArchive' => array('int'),
		'expandComment' => array('int'),
		'expandTrackback' => array('int'),
		'recentNoticeLength' => array('int'),
		'recentEntryLength' => array('int'),
		'recentCommentLength' => array('int'),
		'recentTrackbackLength' => array('int'),
		'linkLength' => array('int'),
		'useRelTag' => array('int')
	)
);
require ROOT . '/lib/includeForOwner.php';
requireStrictRoute();
if (setSkinSetting($owner, $_POST)) {
	printRespond(array('error' => 0));
} else {
	printRespond(array('error' => 1, 'msg' => mysql_error()));
}
?>
