<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/21
 * Time: 13:49
 */
header('Content-type:text/html;charset=utf-8');
if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["post_kind"])) {
    $posttitle = $_POST["title"];
    $postcontent = $_POST["content"];
    $postkind = $_POST["post_kind"];
    $mycookie = $_COOKIE['mycookie'];
    list($name, $pass) = explode(":", $mycookie);
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
    if (!$link) {
        die("连接失败: " . mysqli_connect_error());
        mysqli_close($link);
        return;
    }
    if (!mysqli_select_db($link, DB_NAME)) {//选择数据库
        die("连接失败: " . mysqli_connect_error());
        mysqli_close($link);
        return;
    } else {
        $now_time = date("Y-m-d H:i:s",time());
        $q = "SELECT * FROM fr_user WHERE user_login = '{$name}'";
        $result = mysqli_query($link, $q);
        $row = mysqli_fetch_row($result);
        if ($row == null) {
            echo "<script> alert('提交失败'); </script>";
            return;
        }
        $q = "INSERT INTO fr_post(ID, post_author_ID, post_time,
        post_level_limit, post_title, post_content, post_kind, 
        post_comment_number, post_statue) 
        VALUES (null,'{$row[0]}','{$now_time}',
        0,'{$posttitle}','{$postcontent}','{$postkind}',
        0,'0')";//设置插入指令
        $result = mysqli_query($link, $q);//执行查询
        if ($result == false)
            echo "<script> alert('提交失败'); </script>";
        mysqli_close($link);
        header('location:forum-header.php');
    }
} else {
    header('location:forum-header.php');
}