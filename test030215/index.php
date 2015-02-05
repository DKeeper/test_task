<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:10
 * Created by JetBrains PhpStorm.
 */
require_once(__DIR__.'/helpers.php');
$config = require_once('config.php');

if(isset($_COOKIE['language'])){
    $config['language'] = $_COOKIE['language'];
}
i18n::init($config);

$validateRules = [
    'login' => $config['rules']['login'],
    'password' => $config['rules']['password'],
];

if(isset($_POST['LoginForm'])){
    $db = new DBwrapper();
    $db->init($config['db']);

    $validateErr = [];

    $login = $_POST['LoginForm']['login'];
    $pass = $_POST['LoginForm']['password'];

    if(empty($login)){
        $validateErr['login'] = i18n::t('Login required');
    }
    if(empty($pass)){
        $validateErr['password'] = i18n::t('Password required');
    }

    if(!empty($validateErr)){
        renderLogin($login,$pass,$validateErr,$validateRules);
        return;
    }

    foreach($config['rules']['login'] as $rule){
        if($rule['type']=='regExp')
            $validateErr['login'] = validateRegExp($login,$rule);

        if(isset($validateErr['login'])){
            renderLogin($login,$pass,$validateErr,$validateRules);
            return;
        }
    }
    $res = $db->findOne('SELECT * FROM user WHERE login LIKE :login AND password LIKE :password',[':login'=>$login,':password'=>md5($pass)]);
    if($res){
        renderProfile($res);
    } else {
        renderLogin($login,$pass,['summary'=>i18n::t('Login or Password invalid')],$validateRules);
    }
    $t = 0;
} else {
    renderLogin('','',[],$validateRules);
}
?>