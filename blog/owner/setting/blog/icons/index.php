<?php
define('ROOT', '../../../../..');
$IV = array(
	'POST' => array(
		'mode' => array(array('0','1'), 'mandatory' => false),
		'blogIconSize' => array(array('0','16','32','48'), 'mandatory' => false),
		'deleteLogo' => array('string', 'default' => NULL),
		'deleteBlogIcon' => array('string', 'default' => NULL),
		'deleteFavicon' => array('string', 'default' => NULL)
	),
	'FILES' => array(
		'blogIcon' => array('file', 'mandatory' => false),
		'favicon' => array('file', 'mandatory' => false),
		'logo' => array('file', 'mandatory' => false)
	),
	'SERVER' => array(
		'HTTP_REFERER' => array('string')
	)
);
require ROOT . '/lib/includeForOwner.php';

// 로고 처리.
if ($_POST['deleteLogo'] == "yes") {
	removeBlogLogo($owner);
}

if (!empty($_FILES['logo']['tmp_name'])) {
	$fileExt = Path::getExtension($_FILES['logo']['name']);
	
	if (($fileExt != '.gif') && ($fileExt != '.jpg') && ($fileExt != '.png')) {
		print ('alert("' . _t('변경하지 못했습니다.') . '");');
	} else { 
		if (changeBlogLogo($owner, $_FILES['logo']) === false) {
			print ('alert("' . _t('변경하지 못했습니다.') . '");');
		}
	}
}

// 파비콘 처리.
if ($_POST['deleteFavicon'] == "yes") {
	unlink(ROOT."/attach/$owner/favicon.ico");
}

if (!empty($_FILES['favicon']['tmp_name'])) {
	if (Path::getExtension($_FILES['favicon']['name']) != '.ico') {
		print ('alert("' . _t('변경하지 못했습니다.') . '");');
	} else { 
		requireComponent('Tattertools.Data.Attachment');
		Attachment::confirmFolder();
	
		if (move_uploaded_file($_FILES['favicon']['tmp_name'], ROOT."/attach/$owner/favicon.ico")) {
			@chmod(ROOT . "/attach/$owner/favicon.ico", 0666);
		}
	}
}

// 블로그 아이콘 처리.
if ($_POST['deleteBlogIcon'] == "yes") {
	unlink(ROOT."/attach/$owner/index.gif");
}

if (!empty($_FILES['blogIcon']['tmp_name'])) {
	$fileExt = Path::getExtension($_FILES['blogIcon']['name']);
	
	if (!in_array($fileExt, array('.gif', '.jpg', '.jpeg', '.png'))) {
		print ('alert("' . _t('변경하지 못했습니다.') . '");');
	} else { 
		requireComponent('Tattertools.Data.Attachment');
		Attachment::confirmFolder();

		if (move_uploaded_file($_FILES['blogIcon']['tmp_name'], ROOT . "/attach/$owner/index.gif")) {
			@chmod(ROOT . "/attach/$owner/index.gif", 0666);
		}
	}
}

if (in_array($_POST['blogIconSize'], array("0", "16", "32", "48"))) {
	setUserSetting("blogIconSize", $_POST['blogIconSize']);
}

header("Location: ".$_SERVER['HTTP_REFERER']);
?>
