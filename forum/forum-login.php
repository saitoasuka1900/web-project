<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/17
 * Time: 9:31
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
if (isset($_POST["login"]) && $_POST["login"] == "Login") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    //连接数据库
    if (!$link) {
        die("连接失败: " . mysqli_connect_error());
        mysqli_close($link);
        header("refresh:0;url=index.html");
    }
    else {
        if (!mysqli_select_db($link, DB_NAME)) {//选择数据库
            die("连接失败: " . mysqli_connect_error());
            mysqli_close($link);
            header("refresh:0;url=index.html");
        } else {
            $q = "SELECT * FROM fr_user WHERE user_login = '{$username}' AND user_pass = '{$password}'";//设置查询指令
            $result = mysqli_query($link, $q);//执行查询
            $row = mysqli_fetch_row($result);
            if ($row == null) {
                echo "<script> alert('用户名或密码错误'); </script>";
                header("refresh:0;url=index.html");
            } else {
                $var = 'mycookie';
                $_COOKIE[$var] = $username.":".$password;
                setcookie($var, $username.":".$password);//赋值两次使cookie即时生效
                require_once(ABSPATH . 'forum-header.php');
            }
            unset($link);
        }
    }
} else {
    header('location:index.html');
}