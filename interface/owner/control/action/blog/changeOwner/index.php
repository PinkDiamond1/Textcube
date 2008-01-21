<?php
/// Copyright (c) 2004-2008, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
require ROOT . '/lib/includeForBlogOwner.php';

$IV = array(
	'GET' => array(
		'owner' => array('id'),
		'blogid' => array('id')
	) 
);
requireStrictRoute();

$blogid=$_GET['blogid'];
$userid=$_GET['owner'];

$sql = "UPDATE `{$database['prefix']}Teamblog` SET acl = 3 WHERE blogid = ".$blogid." and acl = " . BITWISE_OWNER;
POD::execute($sql);

$acl = POD::queryCell("SELECT acl FROM {$database['prefix']}Teamblog WHERE blogid='$blogid' and userid='$userid'");

if( $acl === null ) { // If there is no ACL, add user into the blog.
	POD::query("INSERT INTO `{$database['prefix']}Teamblog`  
		VALUES('$blogid', '$userid', '".BITWISE_OWNER."', UNIX_TIMESTAMP(), '0')");
}
else {
	$sql = "UPDATE `{$database['prefix']}Teamblog` SET acl = ".BITWISE_OWNER." 
		WHERE blogid = ".$blogid." and userid = " . $userid;
	POD::execute($sql);
}

respond::PrintResult(array('error' => 0));
?>