<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/6/22
 * Time: 12:35
 */
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
if(isset($_GET['postid'])) {
    $postid = $_GET['postid'];
}
else{
    die("error");
}
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
$q = "SELECT * FROM fr_post WHERE ID = $postid";
$result = mysqli_query($link, $q);
$post = mysqli_fetch_row($result);
if(isset($_COOKIE['mycookie']) && $_COOKIE['mycookie'] != null){
    list($name, $pass) = explode(":", $_COOKIE['mycookie']);
}
?>

<script src="js/postFunction.js"></script>
<link rel="stylesheet" type="text/css" href="css/w3.css">
<div class="w3-teal">
    <div class="w3-container">
        <h1>Forum Post Page</h1>
        <h2 style="position: relative;left: 30%"><?php
            $title = "title:".$post[4];
            if(isset($_COOKIE['mycookie']) && $_COOKIE['mycookie'] != null){
                $title = "user:".$name."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$title;
            }
            echo $title;
            ?>
        </h2>
    </div>
</div>
<div id="submit-comment" style="display:none;position: relative;left: 25%;width: 50%;height: 50%;top: 10%">
    <form class="w3-container w3-light-grey" method="post" style="height: 100%" action="comment-submit.php">
        <label>Content</label>
        <textarea class="w3-input w3-border" type="text" name="content" style="height: 70%"></textarea>
        <button class="w3-btn w3-round-large w3-white w3-right" name="pass" type="submit" style="position: relative;top: 5%" value=<?php echo $post[0];?>>提交</button>
    </form>
</div>
<?php
$html = "<div id='post' style='position: relative;width: 50%;left: 25%'><div class='w3-white' style='height: 2%'></div>";
$q = "SELECT * FROM fr_user WHERE ID = $post[1]";
$poster_result = mysqli_query($link, $q);
$poster = mysqli_fetch_row($poster_result);
$html = $html."<div class='w3-light-grey' style='position: relative;height: 200px'>";
$html = $html."<a target='_blank' style='font-size: 16px'>标题: $post[4]</a>";
$html = $html."<p>内容: $post[5]</p>";
$html = $html."<label class='w3-right' style='position: relative;top: 38%;text-align: right'>poster: $poster[1] 年龄: $poster[4] 性别: $poster[5] 职业: $poster[6] 等级: $poster[7]<br>评论数: $post[7], $post[2]</label>";
$html = $html."<button class='w3-btn w3-left' style='position: relative;top: 43%' onclick='commentSubmit()'>评论</button>";
$html = $html."</div><div class='w3-white' style='height: 2%'></div>";

$q = "SELECT * FROM fr_comment WHERE comment_post_ID = $post[0] ORDER BY comment_time DESC ";
$comment_result = mysqli_query($link, $q);
while($comment = mysqli_fetch_array($comment_result)){
    $q = "SELECT * FROM fr_user WHERE ID = $comment[1]";
    $replier_result = mysqli_query($link, $q);
    $replier = mysqli_fetch_row($replier_result);
    $html = $html."<div class='w3-light-grey' style='height: 200px'>";
    $html = $html."<p>内容: $comment[4]</p>";
    $html = $html."<label class='w3-right' style='position: relative;top: 72%'>replier: $replier[1] 年龄: $replier[4] 性别: $replier[5] 职业: $replier[6] 等级: $replier[7], $comment[3]</label>";
    $html = $html."</div><div class='w3-white' style='height: 2%'></div>";
}
$html = $html."</div>";
echo $html;
mysqli_close($link);
?>