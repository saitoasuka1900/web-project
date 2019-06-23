<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/17
 * Time: 9:20
 */
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (!defined('DB_NAME')) {
    /**加载论坛数据库配置*/
    require_once(ABSPATH . 'forum-config.php');
}
if (empty($_COOKIE['mycookie']) == false && $_COOKIE['mycookie'] != null) {
    $mycookie = $_COOKIE['mycookie'];
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    list($name, $pass) = explode(":", $mycookie);
    $q = "SELECT * FROM fr_user WHERE user_login = '{$name}'";
    $result = mysqli_query($link, $q);
    $user = mysqli_fetch_row($result);
    $userstatus = $user[3];
    mysqli_close($link);
} else {
    $userstatus = 0;
}
?>
<script src="js/menuFunction.js"></script>
<link rel="stylesheet" type="text/css" href="css/w3.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>

<div class="w3-overlay" style="cursor:pointer" id="myOverlay"></div>
<div class="w3-sidebar w3-bar-block w3-animate-left" style="display:none;z-index:5" id="PostMenu">
    <button onclick="closePostMenu()" class="w3-bar-item w3-button w3-large">关闭论坛菜单 &times;</button>
    <h5 class="w3-bar-item">帖子类型</h5>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(0)">学习</button>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(1)">运动</button>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(2)">游玩</button>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(3)">美食</button>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(4)">日常</button>
    <button class="w3-bar-item w3-button tablink" onclick="PostMenuFunction(5)">其他</button>
</div>
<script type="text/javascript">
    function logout() {
        //按钮单击时执行
        $.ajax({
            type: "POST",
            url: "forum-logout.php",
            success: function () {
                window.location.href = "forum-header.php";
            }
        });
    }
</script>
<div class="w3-sidebar w3-bar-block w3-animate-right" style="display:none;z-index:5;right:0" id="ManageMenu">
    <?php
    $exp_limit = array(50,150,300,500,750,1050,1400,1800,2200,9999999999);
    if (empty($_COOKIE['mycookie']) == false && $_COOKIE['mycookie'] != null) {
        echo "<p>user: ".$name."</p>";
        echo "<p>lv:".$user[7]."</p>exp:(".$user[8]."/".$exp_limit[$user[7]].")";
    }
    ?>
    <button onclick="closeManageMenu()" class="w3-bar-item w3-button w3-large">关闭管理菜单 &times;</button>
    <button class="w3-bar-item w3-button tablink" onclick="logout()">注销</button>
    <h5 class="w3-bar-item">个人管理</h5>
    <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(0)">我的帖子</button>
    <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(1)">我的评论</button>
    <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(2)">待审核/未通过</button>
    <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(3)">发帖</button>
    <?php
    if ($userstatus == 1 || $userstatus == '1') {
        echo '
        <h5 class="w3-bar-item">论坛管理</h5>
        <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(4)">帖子审核</button>
        <button class="w3-bar-item w3-button tablink" onclick="ManageMenuFunction(5)">帖子管理</button>
        ';
    }
    ?>
</div>
<div class="w3-teal">
    <button class="w3-button w3-teal w3-xlarge w3-left" onclick="openPostMenu()">&#9776;</button>
    <button class="w3-btn w3-round-large w3-white w3-right" onclick="turn_to_login()" id="login_btn">登录</button>
    <button class="w3-button w3-teal w3-xlarge w3-right" onclick="openManageMenu()" id="manage_menu">&#9776;</button>

    <div class="w3-container">
        <h1>Forum Home Page</h1>
    </div>

</div>

<?php
if (empty($_COOKIE['mycookie']) == false && $_COOKIE['mycookie'] != null) {
    echo "
    <script type='text/javascript'>
    document.getElementById(\"manage_menu\").style.display = \"block\";
    document.getElementById(\"login_btn\").style.display = \"none\";
    </script>
    ";
} else {
    echo "
    <script type='text/javascript'>
    document.getElementById(\"manage_menu\").style.display = \"none\";
    document.getElementById(\"login_btn\").style.display = \"block\";
    </script>
    ";
}
echo '
<div id="submit-post" style="display:none;position: relative;left: 25%;width: 50%;height: 50%;top: 5%">
    <div class=\'w3-white\' style=\'position: relative;height: 5%;left: 49%;font-size: 20px\'>发帖</div>
    <form class="w3-container w3-light-grey" method="post" style="position: relative;height: 100%;top: 5%" action="post-submit.php">
        <label>Title</label>
        <input class="w3-input w3-border" type="text" name="title">
        <label>Content</label>
        <textarea class="w3-input w3-border" type="text" name="content" style="height: 50%"></textarea>
        <button class="w3-btn w3-round-large w3-white w3-right" id="pass" type="submit" style="position: relative;top: 5%">提交</button>
        <select class="w3-select w3-border w3-left" name="post_kind" style="position: relative;width: 30%;top: 1%">
        <option value="" disabled selected>Choose post kind</option>
        <option value="学习">学习</option>
        <option value="运动">运动</option>
        <option value="游玩">游玩</option>
        <option value="美食">美食</option>
        <option value="日常">日常</option>
        <option value="其他">其他</option>
        </select>
    </form>
</div>
';
$post_kind = array('学习', '运动', '游玩', '美食', '日常', '其他');
$kind_id = array('learn', 'sport', 'play', 'food', 'life', 'other');
for ($i = 0; $i < 6; $i += 1) {
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_post WHERE post_kind LIKE \"{$post_kind[$i]}\" AND post_statue LIKE '1' ORDER BY post_time DESC ";
    $result = mysqli_query($link, $q);
    $html = "<div id=\"{$kind_id[$i]}\" style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>$post_kind[$i]</div>";
    $url = "post-show.php?postid=";
    while($row = mysqli_fetch_array($result)){
        $newurl = $url.$row[0];
        $q = "SELECT * FROM fr_user WHERE ID = $row[1]";
        $new_result = mysqli_query($link, $q);
        $new_row = mysqli_fetch_row($new_result);
        $html = $html."<div class='w3-light-grey' style='position: relative;height: 200px'>";
        $html = $html."<a href=$newurl target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none'>标题: $row[4]</a>";
        $html = $html."<p>内容: $row[5]</p>";
        $html = $html."<label class='w3-right' style='position: absolute;top: 90%;right: 0%'>poster: $new_row[1] 评论数: $row[7], $row[2]</label>";
        $html = $html."</div><div class='w3-white' style='height: 2%'></div>";
    }
    $html = $html."</div>";
    echo $html;
    mysqli_close($link);
}
if (empty($_COOKIE['mycookie']) == false && $_COOKIE['mycookie'] != null){
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_post WHERE post_author_ID = $user[0] AND post_statue LIKE '1' ORDER BY post_time DESC ";
    $result = mysqli_query($link, $q);
    mysqli_close($link);
    $html = "<div id='passed-post' style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>我的帖子</div>";
    $url = "post-show.php?postid=";
    while($row = mysqli_fetch_array($result)){
        $newurl = $url.$row[0];
        $html = $html."<form class='w3-light-grey' method='post' style='height: 200px' action='post-delete.php'>";
        $html = $html."<a href=$newurl target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none' name='postid' value=\"{$row[0]}\">标题: $row[4]</a>";
        $html = $html."<p>内容: $row[5]</p>";
        $html = $html."<label class='w3-right' style='position: relative;top: 50%'>评论数: $row[7], $row[2]</label>";
        $html = $html."<button class='w3-btn w3-left' style='position: relative;top: 40%' type='submit'>删除</button>";
        $html = $html."</form><div class='w3-white' style='height: 2%'></div>";
    }
    $html = $html."</div>";
    echo $html;

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_comment WHERE comment_author_ID = $user[0] ORDER BY comment_time DESC ";
    $result = mysqli_query($link, $q);
    $html = "<div id='my-comment' style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>我的评论</div>";
    $url = "post-show.php?postid=";
    while($row = mysqli_fetch_array($result)){
        $q = "SELECT * FROM fr_post WHERE ID = $row[2]";
        $new_result = mysqli_query($link, $q);
        $new_row = mysqli_fetch_array($new_result);
        $newurl = $url.$new_row[0];
        $html = $html."<form class='w3-light-grey' method='post' style='height: 200px' action='comment-delete.php'>";
        $html = $html."<a href=$newurl target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none'>回复: $new_row[4]</a>";
        $html = $html."<p>内容: $row[4]</p>";
        $html = $html."<label class='w3-right' style='position: relative;top: 50%'>$row[3]</label>";
        $html = $html."<button class='w3-btn w3-left' style='position: relative;top: 40%' type='submit' name='commentid' value=$row[0]>删除</button>";
        $html = $html."</form><div class='w3-white' style='height: 2%'></div>";
    }
    mysqli_close($link);
    $html = $html."</div>";
    echo $html;

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_post WHERE post_author_ID = $user[0] AND post_statue NOT LIKE '1' ORDER BY post_statue, post_time DESC ";
    $result = mysqli_query($link, $q);
    mysqli_close($link);
    $html = "<div id='checked-post' style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>待审核/未通过</div>";
    while($row = mysqli_fetch_array($result)){
        $html = $html."<div class='w3-light-grey' style='height: 200px'>";
        if($row[8] == '2'){
            $row[4] = $row[4]."(被退回)";
        }
        else{
            $row[4] = $row[4]."(待审核)";
        }
        $html = $html."<form class='w3-light-grey' method='post' style='height: 100%' action='post-delete.php'>";
        $html = $html."<a target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none'>标题: $row[4]</a>";
        $html = $html."<p>内容: $row[5]</p>";
        $html = $html."<button class='w3-btn w3-right' style='position: relative;top: 43%' type='submit' name='postid' value=$row[0]>删除</button>";
        $html = $html."</form></div><div class='w3-white' style='height: 2%'></div>";
    }
    $html = $html."</div>";
    echo $html;
}

if ($userstatus == 1 || $userstatus == '1'){
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_post WHERE post_statue LIKE '0' ORDER BY post_time DESC ";
    $result = mysqli_query($link, $q);
    mysqli_close($link);
    $html = "<div id='post-check' style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>帖子审核</div>";
    $forumname = "postcheck";
    $cnt = 0;
    while($row = mysqli_fetch_array($result)){
        $cnt += 1;
        $newforumname = $forumname.$cnt;
        $html = $html."<form class='w3-light-grey' id=$newforumname class='w3-light-grey' method='post' style='height: 200px' action='post-check.php'>";
        $html = $html."<a target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none'>标题: $row[4]</a>";
        $html = $html."<p>内容: $row[5]</p>";
        $html = $html."<div style='position: relative;top: 43%'>";
        $html = $html."<button class='w3-btn w3-left' type='submit' name='pass' value=$row[0]>通过</button>";
        $html = $html."<button class='w3-btn w3-right' type='submit' name='fail' value=$row[0]>退回</button></div>";
        $html = $html."</form><div class='w3-white' style='height: 2%'></div>";
    }
    $html = $html."</div>";
    echo $html;

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_set_charset($link,'utf8');
    mysqli_select_db($link, DB_NAME);
    $q = "SELECT * FROM fr_post WHERE post_statue LIKE '1' ORDER BY post_time DESC ";
    $result = mysqli_query($link, $q);
    $html = "<div id='post-manage' style='position: relative;display: none;width: 50%;left: 25%'><div class='w3-white' style='position: relative;height: 5%;left: 49%;font-size: 20px'>帖子管理</div>";
    $url = "post-show.php?postid=";
    while($row = mysqli_fetch_array($result)){
        $newurl = $url.$row[0];
        $q = "SELECT * FROM fr_user WHERE ID = $row[1]";
        $new_result = mysqli_query($link, $q);
        $new_row = mysqli_fetch_row($new_result);
        $html = $html."<form class='w3-light-grey' method='post' style='position: relative;height: 200px' action='post-delete.php'>";
        $html = $html."<a href=$newurl target='_blank' title=$row[4] style='font-size: 16px;text-decoration: none'>标题: $row[4]</a>";
        $html = $html."<p>内容: $row[5]</p>";
        $html = $html."<label class='w3-right' style='position: absolute;top: 90%;right: 0%'>poster: $new_row[1] 评论数: $row[7], $row[2]</label>";
        $html = $html."<button class='w3-btn w3-left' style='position: absolute;top: 80%' type='submit' name='postid' value=$row[0]>删除</button>";
        $html = $html."</form><div class='w3-white' style='height: 2%'></div>";
    }
    $html = $html."</div>";
    echo $html;
    mysqli_close($link);
}
?>

