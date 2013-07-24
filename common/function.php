<?php
function maddslashes($string) {
	if(get_magic_quotes_gpc()) return $string;
	if(is_array($string)) {
		if(!empty($string)) {
			foreach($string as $key => $val) {
				if(!empty($val)) {
					$string[$key] = maddslashes($val);
				}
			}
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}
function getRequestUri()
{
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])) // IIS
			$requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
		else if(isset($_SERVER['REQUEST_URI'])) {
			$requestUri = $_SERVER['REQUEST_URI'];
			if(! empty($_SERVER['HTTP_HOST'])) {
				if(strpos($requestUri ,$_SERVER['HTTP_HOST']) !== false)
					$requestUri = preg_replace('/^\w+:\/\/[^\/]+/' ,'' ,$requestUri);
			} else
				$requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i' ,'' ,$requestUri);
		} else if(isset($_SERVER['ORIG_PATH_INFO']))             // IIS 5.0 CGI
		{
			$requestUri = $_SERVER['ORIG_PATH_INFO'];
			if(! empty($_SERVER['QUERY_STRING']))
				$requestUri .= '?' . $_SERVER['QUERY_STRING'];
		} 

	return $requestUri;

}
function get($key)
{
	switch (true) {
		case isset($_GET[$key]) :
			return maddslashes($_GET[$key]);
		case isset($_POST[$key]) :
			return maddslashes($_POST[$key]);
	}
	return null;
}
function parseUrl($rewrite)
{
	$uri = getRequestUri();
	preg_match('/([^.|?]*)/' ,$uri ,$urimatch);
	$uri = $urimatch[1];
	foreach($rewrite as $key => $map) {
		$keystr = str_replace('/' ,'\/' ,$key);
		$pregkeys = array();
		if(preg_match_all('/<(.*?)>/' ,$key ,$matches)) {
			$pregkeys = $matches[1];
		}
		$pattern = "/^{$keystr}$/i";
		$params = $map;
		if(preg_match($pattern ,$uri ,$match)) {
			foreach($pregkeys as $k => $v) {
				$params[$v] = isset($map[$v]) ? $map[$v] : $match[$v];
			}
			return $params;
		}
	}
	return array();
}
function parseFilter()
{
	$filter = get('filter');
	$filter_arr = array();
	if(!empty($filter))
	{
		$filter_one = explode("-",$filter);

		if(!empty($filter_one))
			foreach($filter_one as $k => $cond)
			{
				$cond_arr = explode("_",$cond);
				if(!empty($cond_arr) && isset($cond_arr[0]) && isset($cond_arr[1]))
				{
					$filter_arr[$cond_arr[0]] = urldecode($cond_arr[1]);
				}
			}
	}
	return $filter_arr;
}
function createUrl($action, $params = array(), $query = array())
{
	global $rewrite_config;
	$rewrite = $rewrite_config;
	$pattern = '/(\(\?P<(.*?)>(?:.*?)\))/';

	$values = array("action"=>$action);
	$values = array_merge($values ,$params);
	foreach($rewrite as $key => $map) {
		if(preg_match_all($pattern ,$key ,$matches)) {
			$rewritekey = array_combine($matches[1] ,$matches[2]);
		}
		if((! isset($map['action']) || $action == $map['action'])) {
			if(! empty($rewritekey)) {
				foreach($rewritekey as $k => $v) {
					$rewritevalue[$k] = isset($values[$v]) ? $values[$v] : '';
				}
				$uri = strtr($key ,$rewritevalue);
			} else {
				$uri = $key;
			}
			$uri = str_replace('?' ,'' ,$uri);
			$uri = str_replace('//' ,'/' ,$uri);
			$querystr = http_build_query($query);
			if(!empty($query) && !empty($querystr))
				$uri = $uri."?".$querystr;
			return $uri;
		}
	}

}
function buildPageUrl($page, $action, $filter='')
{
	return createUrl('dianying',array('filter'=>$filter,'page'=>$page));
}
function pageHtml($page,$params,$showpage=6)
{
	$currentcss = 'disabled';
	$pagecount = $page['pagecount'];
	$current = $page['page'];
	$html = "";
	if($current != 1 && $pagecount >0)
		$html .= '<li><a href="' . buildPageUrl(($current - 1),$params['action'],$params['filter']) . '">上一页</a></li>';
	if($pagecount <= $showpage) {
		for($i = 1; $i <= $pagecount; $i ++) {
			$class = "";
			if($i == $current)
				$class = "class=\"$currentcss\"";
			$html .= '<li '.$class.'><a ' .' href="' .buildPageUrl($i ,$params['action'],$params['filter']). '">' . $i . '</a></li>';
		}
	} else {
		$startpage = ceil($showpage / 2);
		$start = $current - $startpage;
		if($start <= 0)
			$start = 1;
		if($start != 1) {
			$html .= '<li><a href="' .buildPageUrl( 1,$params['action'],$params['filter']).'">1</a></li>';
			$html .= "<li ><a href=\"#\">...</a></li>";
		}

		for($i = $start; $i <= $current; $i ++) {
			$class = "";
			if($i == $current)
				$class = "class=\"$currentcss\"";
				$html .= '<li '.$class.'><a ' .' href="' . buildPageUrl( $i,$params['action'],$params['filter']) . '">' . $i . '</a></li>';
		}
		$end = $current + $startpage;
		if($end >= $pagecount)
			$end = $pagecount;
		for($i = $current + 1; $i <= $end; $i ++) {
			$html .= '<li><a href="' . buildPageUrl( $i,$params['action'],$params['filter']). '">' . $i . '</a></li>';
		}

		if($end != $pagecount) {
			$html .= "<li><a href=\"#\">...</a></li>";
			$html .= '<li><a href="' . buildPageUrl( $pagecount ,$params['action'],$params['filter']). '">' . $pagecount . '</a></li>';
		}
	}
	if($current != $pagecount && $pagecount >0)
		$html .= '<li><a href="' . buildPageUrl(($current + 1),$params['action'],$params['filter']) . '">下一页</a></li>';
	return $html;

}
function getPage($select_sql, $pagesize = 18)
{
	global $db;
	$page = get('page');
	if( !empty($page) && is_numeric($page)) {
		$page = get('page');
		
	} else {
		$page = 1;
	}
	$row = $db->find($select_sql['countsql']);
	$allnum  = $row['num'];
	
	$pagecount = ceil($allnum / $pagesize);
	
	$start = ($page-1) * $pagesize;
	$end = $page * $pagesize; 
	return array('start'=>$start, 'end'=>$pagesize, 'pagecount'=>$pagecount,'allnum'=>$allnum, 'page'=>$page, 'pagesize'=>$pagesize);

}
/*
select * from vgroups as a 
	left join 
		vcatevgroup  as b   
on (b.vgroupid = a.id)   
	left join 
		vpeoplevgroup  as c 
on (c.vgroupid=a.id) 
	where c.vpeopleid=1 and  b.vcateid=1 
*/
function buildFilter($params)
{
	$filter = array();
	foreach($params as $k => $v)
	{
		if(!empty($v))
		$filter[] = $k."_".$v;
		else 
		 $filter[] = $k;
	}
	if(!empty($filter))
	return implode("-", $filter);
}


function mysubstr($str, $len = 12, $dot = true, $encoding='utf-8')
{
	$strlen = mb_strlen($str, $encoding);
	$substr = mb_substr($str, 0, $len, $encoding);
	if($strlen > $len && $dot == true)
	{
		$substr .= "...";
	}
	return $substr;
}
function mystrlen($str,  $encoding='utf-8')
{
	$strlen = mb_strlen($str, $encoding);
	return $strlen;
}

// $string 明文 或 密文
// $isEncrypt 是否加密
// $key 密匙
// 采用SHA1生成密匙簿，超过300个字符使用ZLIB压缩
function dencrypt($string , $key, $isEncrypt = true) {
	if (!isset($string{0}) || !isset($key{0})) {
		return false;
	}

	$dynKey = $isEncrypt ? hash('sha1', microtime(true)) : substr($string, 0, 40);
	$fixedKey = hash('sha1', $key);

	$dynKeyPart1 = substr($dynKey, 0, 20);
	$dynKeyPart2 = substr($dynKey, 20);
	$fixedKeyPart1 = substr($fixedKey, 0, 20);
	$fixedKeyPart2 = substr($fixedKey, 20);
	$key = hash('sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2);

	$string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : (isset($string{339}) ? gzuncompress(base64_decode(substr($string, 40))) : base64_decode(substr($string, 40)));

	$n = 0;
	$result = '';
	$len = strlen($string);

	for ($n = 0; $n < $len; $n++) {
		$result .= chr(ord($string{$n}) ^ ord($key{$n % 40}));
	}
	return $isEncrypt ? $dynKey . str_replace('=', '', base64_encode($n > 299 ? gzcompress($result) : $result)) : substr($result, 20, -20);
}
function jumpurl($url)
{
	header("Location:$url");
	exit;
}

function bindValues($sql, $params)
{
	if(! empty($params))
		foreach($params as $name => $value) {
		if($name[0] !== ':')
			$name = ':' . $name;
		$sql = bindValue($sql ,$name ,$value);
	}
	return $sql;

}


function bindValue($sql, $name, $value)
{
	$value=addslashes($value);
	return $sql = str_replace($name ,"'" . $value . "'" ,$sql);

}
function createByRow($row, $table)
{
	global $db;
	$PARAM_PREFIX = ':dbbind';
	$fields = array();
	$values = array();
	$placeholders = array();
	$i = 200;
	foreach($row as $name => $value) {
		$fields[] = $name;
		$placeholders[] = $PARAM_PREFIX . $i;
		$values[$PARAM_PREFIX . $i] = $value;
		$i ++;
	}

	$sql = "INSERT INTO {$table} (" . implode(', ' ,$fields) . ') VALUES (' . implode(', ' ,$placeholders) . ')';

	$sql = bindValues($sql ,$values);
	$db->query($sql);
	return $sql;

}
function updateByRow($row, $table, $where='')
{

	global $db;
	$PARAM_PREFIX = ':dbbind';
	$fields = array();
	$values = array();
	$i = 200;
	foreach($row as $name => $value) {
		$fields[] = $name . '=' .$PARAM_PREFIX . $i;
		$values[$PARAM_PREFIX . $i] = $value;
		$i++;

	}
	$where = empty($where) ? '': 'where '.$where;
	$sql = "UPDATE {$table} SET " . implode(', ' ,$fields) ." ".$where;
	$sql = bindValues($sql ,$values);

	$db->query($sql);

}