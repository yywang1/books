<?
include_once __DIR__ . '/c_page.php';
$tplArray = array('common_html' => $common_html);

function createTables($container) {
		
	$db = $container['db'];
	
	//书籍信息 books
	$create_books_sql = "CREATE TABLE IF NOT EXISTS books(
		bid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(bid),
		bname varchar(100),
		bauthor varchar(100),
		bsummary text,
		brole varchar(100),
		bsize int, 
		btype int(2),
		bstyle int(2),
		beva int,
		bexist boolean,
		bformat varchar(10),
		borig varchar(200),
		bdate date
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
		foreach($db->attr_tags as $key=>$tag) {
			if(!in_array($key, $existFields)) {
				$alter_tags_sql = "alter table tags add " . $key . " boolean";
				if(! $db->query($alter_tags_sql)){
					return 'tags (alter)';
				}
			}
		}
	} else {
		$create_tags_sql = "CREATE TABLE IF NOT EXISTS tags(bid int NOT NULL, PRIMARY KEY(bid),";
		foreach($db->attr_tags as $key=>$tag) {
			if($key != ('t' . count($db->attr_tags))) {
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
		ulastdate date,
		uregdate date
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
		isupload boolean,
		upload_date date,
		isdown boolean,
		down_date date,
		iseva boolean,
		eva_date date
		)";
	if(! $db->query($create_misc_sql)){
		return 'misc (create)';
	}
	
	//搜索 searches
	$create_searches_sql = "CREATE TABLE IF NOT EXISTS searches(
		sid int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(sid),
		skey varchar(50),
		stimes int
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

echo $twig->render('result.html', $tplArray);

?>