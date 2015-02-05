<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:10
 * Created by JetBrains PhpStorm.
 */
require_once(__DIR__ . '/helpers/helpers.php');
$config = require_once('config.php');

if(isset($_COOKIE['language'])){
    $config['language'] = $_COOKIE['language'];
}
i18n::init($config);

$db = new DBwrapper();
$db->init($config['db']);
$user = new LoginForm($db);

if(isset($_POST['LoginForm'])){

    $user->load($_POST['LoginForm']);

    if($user->validate()){
        if($user->find(['login LIKE :login','password LIKE :password'],[':login'=>$user->getAttribute('login'),':password'=>md5($user->getAttribute('password'))])){
            renderProfile($user);
            return;
        } else {
            $user->addError('summary',i18n::t('Login or Password invalid'));
        }
    }
}

renderLogin($user);
?>