<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 9:29
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

$user = new User($db);

if(isset($_POST['RegistrationForm'])){

    if(isset($_FILES['RegistrationForm'])){
        foreach($_FILES['RegistrationForm']['tmp_name'] as $name => $file){
            $_POST['RegistrationForm'][$name] = $file;
        }
    }

    $user->load($_POST['RegistrationForm']);

    $user->setAttribute('password',md5($user->getAttribute('password')));

    if($user->validate()){
        // Copy Image
        foreach($_FILES['RegistrationForm']['name'] as $name => $file){
            if(!is_dir(__DIR__."/upload/")){
                mkdir(__DIR__."/upload/");
            }
            move_uploaded_file($_FILES['RegistrationForm']['tmp_name'][$name],__DIR__."/upload/".$_FILES['RegistrationForm']['name'][$name]);
            $user->setAttribute($name,"/test030215/upload/".$_FILES['RegistrationForm']['name'][$name]);
        }
        if($user->save()){
            renderProfile($user);
            return;
        }
    }
}

renderRegistration($user);