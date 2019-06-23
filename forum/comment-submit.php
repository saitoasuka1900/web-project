<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/22
 * Time: 22:38
 */
header('Content-type:text/html;charset=utf-8');
$postid = $_POST["pass"];
$url = 'location:post-show.php?postid=' . $postid;
if (!isset($_COOKIE['mycookie']) || $_COOKIE['mycookie'] == null) {
    echo '<script>alert("请先登录再评论");</script>';
    header($url);
}
list($name, $pass) = explode(":", $_COOKIE['mycookie']);
$comment = $_POST["content"];
date_default_timezone_set('PRC');
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}
if (!defined('DB_NAME')) {
    /**加载论坛数据库配置*/
    require_once(ABSPATH . 'forum-config.php');
}
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_set_charset($link,'utf8');
//连接数据库
mysqli_select_db($link, DB_NAME);
$now_time = date("Y-m-d H:i:s", time());
$q = "SELECT * FROM fr_user WHERE user_login = '{$name}'";
$result = mysqli_query($link, $q);
$user = mysqli_fetch_row($result);
$q = "INSERT INTO fr_comment(ID, comment_author_ID, comment_post_ID, 
      comment_time, comment_content) VALUES (null,$user[0],$postid,'{$now_time}',
      \"{$comment}\")";//设置插入指令
$result = mysqli_query($link, $q);//执行查询
$exp_limit = array(50,150,300,500,750,1050,1400,1800,2200,9999999999);
if($user[8] + 5 >= $exp_limit[$user[7]]){
    $user[8] = $user[8] + 1 - $exp_limit[$user[7]];
    $user[7] += 1;
}
else{
    $user[8] = $user[8] + 1;
}
$q = "UPDATE fr_user SET user_level = $user[7],user_experience = $user[8] WHERE ID = $user[0]";//设置更新指令
$result = mysqli_query($link, $q);//执行更新
$q = "UPDATE fr_post SET post_comment_number = post_comment_number + 1 WHERE ID = $postid";//设置更新指令
$result = mysqli_query($link, $q);//执行更新
mysqli_close($link);
header($url);