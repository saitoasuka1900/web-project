<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/18
 * Time: 22:53
 */

/**定义ABSPATH为该工程下的路径*/
header('Content-type:text/html;charset=utf-8');
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (!defined('DB_NAME')) {
    /**加载论坛数据库配置*/
    require_once(ABSPATH . 'forum-config.php');
}

if (isset($_POST["register"]) && $_POST["register"] == "register") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $userage = $_POST["age"];
    $userjob = $_POST["job"];
    $usersex = $_POST["sex"];
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    //连接数据库
    if (!$link) {
        die("连接失败: " . mysqli_connect_error());
        mysqli_close($link);
        header("refresh:0;url=index.html");
        return;
    }
    if (!mysqli_select_db($link, DB_NAME)) {//选择数据库
        die("连接失败: " . mysqli_connect_error());
        mysqli_close($link);
        header("refresh:0;url=index.html");
        return;
    } else {
        $q = "SELECT * FROM fr_user WHERE user_login = '{$username}'";//设置判断查询
        $result = mysqli_query($link, $q);//执行查询
        if ($result == false) {
            echo "<script> alert('注册失败'); </script>";
            mysqli_close($link);
            header("refresh:0;url=index.html");
            return;
        }
        $row = mysqli_fetch_row($result);
        if ($row != null) {
            echo "<script> alert('用户名已存在'); </script>";
            header("refresh:0;url=index.html");
            mysqli_close($link);
            return;
        }
        $q = "INSERT INTO fr_user (ID, user_login, user_pass, user_status, user_age, user_sex, user_job, user_level, user_experience)
        VALUES (null,'{$username}','{$password}','0',$userage,'{$usersex}','{$userjob}',0,0)";//设置插入指令
        $result = mysqli_query($link, $q);//执行插入
        if ($result == false) {
            echo "<script> alert('注册失败'); </script>";
        } else {
            echo "<script> alert('注册成功'); </script>";
        }
        header("refresh:0;url=index.html");
        mysqli_close($link);
    }
} else {
    header('location:index.html');
}