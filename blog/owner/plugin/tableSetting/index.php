<?php
/// Copyright (c) 2004-2007, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('ROOT', '../../../..');

require ROOT . '/lib/includeForBlogOwner.php';
require ROOT . '/lib/piece/owner/header.php';
require ROOT . '/lib/piece/owner/contentMenu.php';

if ($blogid == 1) {

?>
						<script type="text/javascript">
							//<![CDATA[
								function deletePluginTable(name, type) {
									if(!confirm("<?php echo _t('해당 테이블을 정말 삭제하시겠습니까? 사용중인 플러그인은 자동으로 해제됩니다.');?>"))
											return;								
									var queryURL = "<?php echo $blogURL;?>/owner/plugin/tableSetting/delete";
									queryURL += '?name=' + encodeURI(name);
									queryURL += '&type=' + type;
									var request = new HTTPRequest("POST", queryURL);
									request.onSuccess = function() {
										alert("<?php echo _t('테이블이 삭제되었습니다.');?>");
										changeList();
									}
									request.onError = function() {
										alert("<?php echo _t('테이블을 지우지 못했습니다.');?>");
									}
									request.send();
								}

								function changeList() {
									document.getElementById("part-plugin-table-list").submit();
								}

								window.addEventListener("load", execLoadFunction, false);
								
								function execLoadFunction() {
								}
							//]]>
						</script>
						
						<form id="part-plugin-table-list" class="part" method="post" action="<?php echo $blogURL."/owner/plugin/tableSetting";?>">
							<h2 class="caption"><span class="main-text"><?php echo _t('플러그인이 생성한 테이블입니다');?></span></h2>
							
							<div class="main-explain-box">
								<p class="explain"><?php echo _t('플러그인이 생성한 테이블입니다. 테이블의 데이터를 삭제할 수 있습니다.');?></p>
							</div>

							<table class="data-inbox" cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th class="title"><span class="text"><?php echo _t('플러그인 이름');?></span></th>
										<th class="version"><span class="text"><?php echo _t('버전');?></span></th>
										<th class="using"><span class="text"><?php echo _t('사용여부');?></span></th>
										<th class="tablename"><span class="text"><?php echo _t('테이블 이름');?></span></th>
										<th class="delete"><span class="text"><?php echo _t('삭제');?></span></th>
									</tr>
								</thead>
								<tbody>
<?php


$likeEscape = array ( '/_/' , '/%/' );
$likeReplace = array ( '\\_' , '\\%' );
$escapename = preg_replace($likeEscape, $likeReplace, $database['prefix']);
$query = "show tables like '{$escapename}%'";
$dbtables = DBQuery::queryColumn($query);

$result = DBQuery::queryRow("show variables like 'lower_case_table_names'");
$dbCaseInsensitive = ($result['Value'] == 1) ? true : false;

$prefix = $database['prefix'];
$definedTables = array("{$prefix}Attachments", "{$prefix}BlogSettings", "{$prefix}BlogStatistics", "{$prefix}Categories", "{$prefix}Comments", "{$prefix}CommentsNotified", "{$prefix}CommentsNotifiedQueue", "{$prefix}CommentsNotifiedSiteInfo", "{$prefix}DailyStatistics", "{$prefix}Entries", "{$prefix}FeedGroupRelations", "{$prefix}FeedGroups", "{$prefix}FeedItems", "{$prefix}FeedReads", "{$prefix}Feeds", "{$prefix}FeedSettings", "{$prefix}FeedStarred", "{$prefix}Filters", "{$prefix}Links", "{$prefix}Plugins", "{$prefix}RefererLogs", "{$prefix}RefererStatistics", "{$prefix}ReservedWords", "{$prefix}ServiceSettings", "{$prefix}Sessions", "{$prefix}SessionVisits", "{$prefix}SkinSettings", "{$prefix}TagRelations", "{$prefix}Tags", "{$prefix}Teamblog", "{$prefix}TeamEntryRelations", "{$prefix}TrackbackLogs", "{$prefix}Trackbacks", "{$prefix}Users", "{$prefix}UserSettings", "{$prefix}XMLRPCPingSettings");

$dbtables = array_values(array_diff($dbtables, $definedTables));
if ($dbCaseInsensitive == true) {
	$tempTables = $definedTables;
	$definedTables = array();
	foreach($tempTables as $table) {
		$table = strtolower($table);
		array_push($definedTables, $table);
	}
	$tempTables = $dbtables;
	$dbtables = array();
	foreach($tempTables as $table) {
		$table = strtolower($table);
		array_push($dbtables, $table);
	}
	$dbtables = array_values(array_diff($dbtables, $definedTables));
}

$query = "select name, value from {$database['prefix']}ServiceSettings WHERE name like 'Database\_%'";
$plugintablesraw = DBQuery::queryAll($query);
$plugintables = array();
foreach($plugintablesraw as $table) {
	$dbname = $database['prefix'] . substr($table['name'], 9);
	$values = explode('/', $table['value'], 2);

	$plugin = $values[0];
	$version = $values[1];
	if (!array_key_exists($plugin .'/'. $version, $plugintables)) {
		$plugintables[$plugin .'/'. $version] = array('plugin' => $plugin, 'version' => $version, 'tables' => array());
	}
	array_push($plugintables[$plugin .'/'. $version]['tables'], $dbname);
	
	if ($dbCaseInsensitive == true) $dbname = strtolower($dbname);
	
	if (($pos = array_search($dbname, $dbtables)) !== false) {
		array_splice($dbtables, $pos, 1);
	}
}

$oddline = true;
foreach($plugintables as $plugindb)
{
	$className = $oddline ? 'odd-line' : 'even-line';
	$oddline = !$oddline;
	
	$activeStatus = false;
	if (in_array($plugindb['plugin'], $activePlugins)) {
		$activeStatus = true;
	}
	
?>
									<tr class="<?php echo $className;?>" onmouseover="rolloverClass(this, 'over')" onmouseout="rolloverClass(this, 'out')">
										<td class="title"><?php echo $plugindb['plugin'];?></td>
										<td class="version"><?php echo $plugindb['version'];?></td>
										<td class="using <?php echo $activeStatus ? 'active-class': 'inactive-class';?>"><?php echo $activeStatus ? _t('사용중'): _t('미사용');?></td>
<?php
	$tables = '<span class="table-name">' . implode('</span>, <span class="table-name">', $plugindb['tables']) . '</span>';
?>
										<td class="tablename"><?php echo $tables;?></td>
										
										<td class="delete"><a id="plugin<?php echo 'a';?>Link" class="delete-button active-class" href="#void" onclick="deletePluginTable('<?php echo $plugindb['plugin'],'/',$plugindb['version'];?>', 1); return false" title="<?php echo _t('이 테이블을 삭제합니다.');?>"><span class="text"><?php echo _t('삭제');?></span></a></td>
									</tr>
<?php
}
foreach($dbtables as $dbname)
{
	$className = $oddline ? 'odd-line' : 'even-line';
	$oddline = !$oddline;
	
?>
									<tr class="<?php echo $className;?>" onmouseover="rolloverClass(this, 'over')" onmouseout="rolloverClass(this, 'out')">
										<td class="title"><?php echo _t('알 수 없음');?></td>
										<td class="version"></td>
										<td class="using"></td>
										<td class="tablename"><span class="table-name"><?php echo $dbname;?></span></td>
										<td class="delete"><a id="plugin<?php echo 'a';?>Link" class="delete-button active-class" href="#void" onclick="deletePluginTable('<?php echo $dbname;?>', 2); return false" title="<?php echo _t('이 테이블을 삭제합니다.');?>"><span class="text"><?php echo _t('삭제');?></span></a></td>
									</tr>
	<?php
}
?>
								</tbody>
							</table>
						</form>
<?php
} else { // when not blogid == 1
?>
	<h2 class="caption"><span class="main-text"><?php echo _t('블로그 소유자만 테이블을 관리할 수 있습니다.');?></span></h2>
<?php
}
require ROOT . '/lib/piece/owner/footer.php';
?>
