<?php
/**
 * @Author: Marte
 * @Date:   2017-09-28 19:24:07
 * @Last Modified by:   name
 * @Last Modified time: 2017-10-23 22:36:03
 */
 session_start();
 define('IN_TG', true);

 define('SCRIPT', 'huixin');
 require dirname(__FILE__).'/includes/common.inc.php';
 include ROOT_PATH.'/includes/register.func.php';
 //引用公共文件common.inc.php
// _login_state();//登录状态判断
 if (!isset($_COOKIE['username'])) {
    _alert_close('请先登录后再进行操作');
 }

 if (isset($_GET['action'])=='write') {
     if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_uger WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
        $_clean=array();
        $_clean['touser']=$_POST['touser'];
        $_clean['formuser']=$_COOKIE['username'];
        $_clean['content']=$_POST['content'];
        $_clean=_mysql_string($_clean);

        _query("INSERT INTO tg_message (
                                tg_touser,
                                tg_formuser,
                                tg_content,
                                tg_date)
                        VALUES(
                                '{$_clean['touser']}',
                                '{$_clean['formuser']}',
                                '{$_clean['content']}',
                                NOW()
                    )");
        if (_affected_rows()==1) {
            mysql_close();
            _session_destroy();
            _alert_close('回信成功');
        }else{
            mysql_close();
            _session_destroy();
            _alert_back('回信失败');
        }
     }else{
        _alert_close('非法登录!');
     }
 }

 if (isset($_GET['formuser'])) {
     if (!!$_rows=_fetch_array("SELECT tg_username FROM tg_uger WHERE tg_username='{$_GET['formuser']}' LIMIT 1")) {
            $_html=array();
            $_html['touser']=_html($_rows['tg_username']);

    }else{
        _alert_close('此用户不存在！');
    }
 }else{
    _alert_back("非法操作！");
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>多用户留言系统——写信息</title>
    <link rel="stylesheet" type="text/css" href="style/1/message.css">
</head>
<body>
    <div id="message">
        <h3>写信息</h3>
        <form action="?action=write" method="post" accept-charset="utf-8">
            <input type="hidden" name="touser" value="<?php echo $_html['touser']?>">
            <dl>
                <dd>To：<?php echo $_html['touser']?></dd>
                <dd><textarea name="content" class="text" maxlength="200"></textarea></dd>
                <dd><input type="submit" name="submit" value="发送" class="submit"></dd>
            </dl>
        </form>
    </div>
</body>
</html>