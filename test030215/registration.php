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

//    $validateErr = [];
//
//    foreach($_POST['RegistrationForm'] as $name => $value){
//        if(isset($config['rules'][$name])){
//            foreach($config['rules'][$name] as $rule){
//                if($rule['type']=='regExp')
//                    $validateErr[$name] = validateRegExp($value,$rule);
//                if($rule['type']=='length')
//                    $validateErr[$name] = validate($value,$rule);
//
//                if(isset($validateErr[$name])) break;
//            }
//            if(is_null($validateErr[$name])) unset($validateErr[$name]);
//        }
//    }
//
//    if(isset($_FILES['RegistrationForm'])){
//        foreach($_FILES['RegistrationForm']['name'] as $name => $file){
//            if(isset($config['rules'][$name])){
//                foreach($config['rules'][$name] as $rule){
//                    if($rule['type']=='file')
//                        $validateErr[$name] = validateFile($_FILES['RegistrationForm']['tmp_name'][$name],$rule);
//
//                    if(isset($validateErr[$name])) break;
//                }
//                if(is_null($validateErr[$name])) unset($validateErr[$name]);
//            }
//        }
//    }

//    if(!empty($validateErr)){
//        renderRegistration($_POST['RegistrationForm'],$validateErr,$config['rules']);
//        return;
//    }
//
//    $userData = $_POST['RegistrationForm'];

    // Copy Image
//    foreach($_FILES['RegistrationForm']['name'] as $name => $file){
//        if(!is_dir(__DIR__."/upload/")){
//            mkdir(__DIR__."/upload/");
//        }
//        move_uploaded_file($_FILES['RegistrationForm']['tmp_name'][$name],__DIR__."/upload/".$_FILES['RegistrationForm']['name'][$name]);
//        $userData[$name] = "/test030215/upload/".$_FILES['RegistrationForm']['name'][$name];
//    }

//    // Save user
//    $sql = "INSERT INTO user
//        (`login`, `password`, `email`, `phone`, `first_name`, `last_name`, `avatar`)
//        VALUES
//        (:l, :p, :e, :ph, :fn, :ln, :a)";
//    if($db->query($sql,[
//        ':l'=>$userData['login'],
//        ':p'=>md5($userData['password']),
//        ':e'=>$userData['email'],
//        ':ph'=>$userData['phone'],
//        ':fn'=>$userData['first_name'],
//        ':ln'=>$userData['last_name'],
//        ':a'=>$userData['avatar']]
//    )){
//        renderProfile($userData);
//    }
}

renderRegistration($user);