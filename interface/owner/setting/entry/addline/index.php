<?php
/// Copyright (c) 2004-2009, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('__TEXTCUBE_HEADER_XML__',true);
require ROOT . '/library/preprocessor.php';

$password = Setting::getBlogSetting('LinePassword',null,true);

if(is_null($password)) {
	$password = md5(generatePassword());
	Setting::setBlogSetting('LinePassword',$password,true);
} 

$provider = new OpenSearchProvider();
$provider->setDescriptor('ShortName',Setting::getBlogSetting('title','TITLE',true));
$provider->setDescriptor('Description',Setting::getBlogSetting('description','DESCRIPTION',true));
$provider->setDescriptor('Url',null);
$provider->addAttribute('/OpenSearchDescription', 'xmlns', 'http://a9.com/-/spec/opensearch/1.1/');
$provider->addAttribute('/OpenSearchDescription/Url','type','text/html');
$provider->addAttribute('/OpenSearchDescription/Url','template',$defaultURL.'/line?key='.$password.'&amp;content={searchTerms}');
$provider->setDescriptor('Language', Setting::getBlogSetting('language','ko-kr',true));
$provider->setDescriptor('OutputEncoding', 'utf-8');
$provider->setDescriptor('InputEncoding','utf-8');
$provider->generate();
echo $provider->_xmlcontent;
?>