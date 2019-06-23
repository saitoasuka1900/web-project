<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/22
 * Time: 14:43
 */
header('Content-type:text/html;charset=utf-8');
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (!defined('DB_NAME')) {
    /**加载论坛数据库配置*/
    require_once(ABSPATH . 'forum-config.php');
}
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_set_charset($link,'utf8');
mysqli_select_db($link, DB_NAME);
$commentid = $_POST['commentid'];
$q = "DELETE FROM fr_comment WHERE ID = $commentid";
$result = mysqli_query($link, $q);
mysqli_close($link);
header("location:forum-header.php");