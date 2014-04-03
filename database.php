<?
include_once __DIR__ . '/includes/global.init.php';

function createTables($container) {
		
	$db = $container['db'];
	$attr_tags = $container['vars']['attr_tags'];
	
	//书籍信息 books
	$create_books_sql = "CREATE TABLE IF NOT EXISTS books(
		bid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(bid),
		bname varchar(100) comment '书名',
		bauthor varchar(100) comment '作者',
		bsummary text comment '简介',
		brole varchar(100) comment '主角',
		bsize int comment '文件体积', 
		btype int(2) comment '类型',
		bstyle int(2) comment '文风',
		beva int comment '好评数',
		bexist boolean comment '文件是否未删除',
		bformat varchar(10) comment '文件格式',
		borig varchar(200) comment '原创网址',
		uid varchar(20) comment '上传者的uid',
		btime timestamp comment '文件上传的时间'
		)";
	if(! $db->query($create_books_sql)){
		return 'books (create)';
	}
	
	//书籍标签 tags
	if($db->isTableExist('tags')) {
		$res = $db->query("SHOW COLUMNS FROM tags");
		$existFields = array();
		while($row = mysql_fetch_row($res)) {
			$existFields[] = $row[0];
		}
		foreach($attr_tags as $key=>$tag) {
			if(!in_array($key, $existFields)) {
				$alter_tags_sql = "alter table tags add " . $key . " boolean";
				if(! $db->query($alter_tags_sql)){
					return 'tags (alter)';
				}
			}
		}
	} else {
		$create_tags_sql = "CREATE TABLE IF NOT EXISTS tags(bid int NOT NULL, PRIMARY KEY(bid),";
		foreach($attr_tags as $key=>$tag) {
			if($key != ('t' . count($attr_tags))) {
				$create_tags_sql .= $key . ' boolean,';
			} else {
				$create_tags_sql .= $key . ' boolean)';
			}
		}
		if(! $db->query($create_tags_sql)){
			return 'tags (create)';
		}
	}
	
	//用户表 users （uctbt:贡献、参与度）
	$create_users_sql = "CREATE TABLE IF NOT EXISTS users(
		uid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(uid),
		uname varchar(20),
		uemail varchar(100),
		upwd varchar(16),
		umoney int,
		uctbt int,
		uvalid boolean,
		ulasttime timestamp,
		uregtime timestamp
		)";
	if(! $db->query($create_users_sql)){
		return 'users (create)';
	}
	
	//用户与书籍的关联 misc
	$create_misc_sql = "CREATE TABLE IF NOT EXISTS misc(
		mid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(mid),
		bid int,
		uid int,
		isdown boolean,
		downtime timestamp,
		iseva boolean,
		evatime timestamp
		)";
	if(! $db->query($create_misc_sql)){
		return 'misc (create)';
	}
	
	//搜索 searches
	$create_searches_sql = "CREATE TABLE IF NOT EXISTS searches(
		sid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(sid),
		skey varchar(50),
		stime timestamp
		)";
	if(! $db->query($create_searches_sql)){
		return 'searches (create)';
	}
	
	return true;
}

$initResult = createTables($container);

if($initResult === true) {
	$tplArray['html_result'] = '<div class="sucDone tac doneMt">&radic; 网站初始化成功</div>';
} else {
	$tplArray['html_result'] = '<div class="failDone tac doneMt">&times; 网站初始化失败: ' + initResult + '</div>';
}

echo $container['twig']->render('result.html', $tplArray);

?>