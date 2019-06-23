<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/23
 * Time: 0:06
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
if(isset($_POST['pass'])){
    $postid = $_POST['pass'];
    $q = "UPDATE fr_post SET post_statue = '1' WHERE ID = $postid";
    $result = mysqli_query($link, $q);
    $q = "SELECT * FROM fr_post WHERE ID = $postid";
    $result = mysqli_query($link, $q);
    $row = mysqli_fetch_row($result);

    $exp_limit = array(50,150,300,500,750,1050,1400,1800,2200,9999999999);
    $q = "SELECT * FROM fr_user WHERE ID = $row[1]";
    $result = mysqli_query($link, $q);
    $row = mysqli_fetch_row($result);
    if($row[8] + 5 >= $exp_limit[$row[7]]){
        $row[8] = $row[8] + 5 - $exp_limit[$row[7]];
        $row[7] += 1;
    }
    else{
        $row[8] = $row[8] + 5;
    }
    $q = "UPDATE fr_user SET user_level = $row[7], user_experience = $row[8] WHERE ID = $row[0]";
    $result = mysqli_query($link, $q);
    mysqli_close($link);
}
else{
    $postid = $_POST['fail'];
    $q = "UPDATE fr_post SET post_statue = '2' WHERE ID = $postid";
    $result = mysqli_query($link, $q);
    mysqli_close($link);
}
header("location:forum-header.php");