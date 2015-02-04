<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:10
 * Created by JetBrains PhpStorm.
 */
require_once(__DIR__.'/helpers.php');
$config = require_once('config.php');

if(isset($_POST['LoginForm'])){
    $db = new DBwrapper();
    $db->init($config['db']);

    $validateErr = [];

    $login = $_POST['LoginForm']['login'];
    $pass = $_POST['LoginForm']['password'];

    if(empty($login)){
        $validateErr['login'] = 'login required';
    }
    if(empty($pass)){
        $validateErr['password'] = 'Password required';
    }

    if(!empty($validateErr)){
        renderLogin($login,$pass,$validateErr,$config['rules']);
        return;
    }

    foreach($config['rules']['login'] as $rule){
        if($rule['type']=='regExp')
            $validateErr['login'] = validateRegExp($login,$rule);

        if(isset($validateErr['login'])){
            renderLogin($login,$pass,$validateErr,$config['rules']);
            return;
        }
    }
    $res = $db->findOne('SELECT * FROM user WHERE login LIKE :login AND password LIKE :password',[':login'=>$login,':password'=>md5($pass)]);
    if($res){
        renderProfile($res);
    } else {
        renderLogin($login,$pass,['summary'=>'Login or Password invalid'],$config['rules']);
    }
    $t = 0;
} else {
    renderLogin('','',[],$config['rules']);
}
?>