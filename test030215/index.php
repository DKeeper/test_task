<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:10
 * Created by JetBrains PhpStorm.
 */
require_once(__DIR__.'/DBwrapper.php');
require_once(__DIR__.'/helpers.php');

function renderProfile($res){
    echo viewPhpFile(__DIR__.'/view/profile.php',[
        'user' => $res,
    ]);
}
/**
 * @param string $user
 * @param string $pass
 * @param array $err
 */
function renderLogin($user='',$pass='',$err=[]){
    echo viewPhpFile(__DIR__.'/view/login.php',[
        'user' => $user,
        'pass' => $pass,
        'err' => $err,
    ]);
}

if(isset($_POST['LoginForm'])){
    $config = require_once('config.php');
    $db = new DBwrapper();
    $db->init($config['db']);

    $validateErr = [];

    $user = $_POST['LoginForm']['username'];
    $pass = $_POST['LoginForm']['password'];

    if(empty($user)){
        $validateErr['user'] = 'User name required';
    }
    if(empty($pass)){
        $validateErr['pass'] = 'Password required';
    }

    if(!empty($validateErr)){
        renderLogin($user,$pass,$validateErr);
        return;
    }

    $validateErr['user'] = validate($user,$config['rules']['user']);

    if(isset($validateErr['user'])){
        renderLogin($user,$pass,$validateErr);
        return;
    }
    $res = $db->findOne('SELECT * FROM user WHERE login LIKE :login AND password LIKE :password',[':login'=>$user,':password'=>md5($pass)]);
    if($res){
        renderProfile($res);
    } else {
        renderLogin($user,$pass,['summary'=>'User or Password invalid']);
    }
    $t = 0;
} else {
    renderLogin();
}
?>