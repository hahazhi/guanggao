<?php
//新闻类型
$type_arr = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5');
//api list
$op_arr = array('add', 'add_post', 'edit', 'edit_post', 'del', 'lists');
//news operation
$op = get('op');
if (!in_array($op, $op_arr)) {
    $op = 'lists';
}
$op();
//operation end

function lists($msg='') {
    $type_arr = $GLOBALS['type_arr'];
    $db = $GLOBALS['db'];
    $page = intval(get('page'));
    $page = $page > 0 ? $page : 1;
    $limit = 15;
    $offset = ($page-1)*$limit;
    $rows = $db->findAll("select * from un_news where status>=0 order by createtime desc limit {$offset},{$limit}");
    $total = $db->find('select count(1) as total from un_news where status>=0');
    $pagecount = ceil($total['total'] / $limit) ;
//    $params = array('action'=>'lists');
//    $page = array('page'=>$page, 'pagecount'=>($total['total']/$limit+1));
    foreach($rows as $row){
        $models[] = array(
            'id' => $row['id'],
            'type' => $type_arr[$row['type']],
            'title' => $row['title'],
            'createtime' => $row['createtime'],
            'content' => $row['content'],
        );
    }

    include TPL_ADMIN . "/news.tpl.php";
}

function add() {
    $type_arr = $GLOBALS['type_arr'];
    include TPL_ADMIN . "/news_add.tpl.php";
}

function add_post() {
    $db = $GLOBALS['db'];
    $title = get('title');
    $type = intval(get('type'));
    $content = get('content');
    $insert_sql = "insert into un_news (title, type, content, createtime) values ('{$title}', '{$type}', '{$content}', now())";
    $db->query($insert_sql);
    if($db->affected()!=1){
        echo "<script type='text/javascript'>alert('添加失败')</script>";
    }
    lists();
}

function edit() {
    $type_arr = $GLOBALS['type_arr'];
    $db = $GLOBALS['db'];
    $id = intval(get('id'));
    $sql = "select * from un_news where id={$id}";
    $news = $db->find($sql);
    include TPL_ADMIN . "/news_edit.tpl.php";
}

function edit_post() {
    $db = $GLOBALS['db'];
    $id = intval(get('id'));
    $type = intval(get('type'));
    $title = get('title');
    $content = get('content');
    $sql = "update un_news set title='{$title}', type='{$type}', content='{$content}' where id={$id} limit 1";
    $db->query($sql);
    if($db->affected() != 1)
        echo "<script type='text/javascript'>alert('新闻无更改')</script>";
    lists();
}

function del() {
    $db = $GLOBALS['db'];
    $ids = get('id');
    foreach($ids as $id){
        $delete_id[] = intval($id);
    }
    $where = implode(',', $ids); 
    $sql = "update un_news set status=-1 where id in ({$where})";
    $db->query($sql);
    if(count($delete_id) != $db->affected())
        echo "<script type='text/javascript'>alert('删除失败')</script>";
    lists();
}

?>