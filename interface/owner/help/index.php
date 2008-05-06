<?php
/// Copyright (c) 2004-2008, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
$IV = array(
	'GET' => array(
		'subject' => array('filename'),
		'lang' => array('string')
		)
	);

require ROOT . '/lib/includeForBlogOwner.php';
if (false) {
	fetchConfigVal();
}
$filename = $_GET['lang'].'.'.$_GET['subject'].'.html';
$shortcutFilename = $_GET['lang'].'.shortcut.html';

header('Content-Type: text/html; charset=utf-8');
if (!file_exists(ROOT . "/interface/owner/help/".$filename)){
	if (!file_exists(ROOT . "/interface/owner/help/".$shortcutFilename)){
		echo _t('죄송합니다. 아직 해당 메뉴에 대한 도움말이 준비되지 않았습니다.');
		exit;
	} else {
		$result = file_get_contents(ROOT . "/interface/owner/help/".$shortcutFilename);
		echo $result;
		exit;
	}
}
$result = file_get_contents(ROOT . "/interface/owner/help/".$filename);
echo $result;
exit;
?>
