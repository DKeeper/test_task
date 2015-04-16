<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:10
 * Created by JetBrains PhpStorm.
 */
session_start();

/**
 * Login = testadmin
 * Password = testadmin
 */
$user = null;

require_once(__DIR__ . '/helpers/helpers.php');
$config = require_once(__DIR__ . '/config/config.php');

define('TEST_BASE_URL',$config['baseUrl']);
define('TEST_APP_URL',$config['appUrl']);

$db = new DBwrapper();
$db->init($config['db']);

if(isset($_SESSION['user'])){
    $user = new User($db);
    $user->load($_SESSION['user']);
}

$q = get('q','');

switch($q){
    case 'delpost':
        if(!isset($user)){
            redirectMain();
        }
        $id = intval(get('id',0));
        $postModel = new Post($db);
        if(!$postModel->findByPk($id)){
            redirectMain();
        }
        $postModel->delete();
        redirectMain();
        break;
    case 'replypost':
        if(!isset($user)){
            redirectMain();
        }
        $id = intval(get('id',0));
        $postParentModel = new Post($db);
        $postModel = new Post($db);
        if(!$postParentModel->findByPk($id)){
            redirectMain();
        }
        $postModel->setAttribute("parent",$id);
        $postData = post("PostForm");
        if(isset($postData)){
            $postModel->load($postData);
            if($postModel->validate() && $postModel->save()){
                redirectMain();
            }
        }
        renderEditPost($postModel,$user);
        break;
    case 'editpost':
        if(!isset($user)){
            redirectMain();
        }
        $id = intval(get('id',0));
        $postModel = new Post($db);
        if(!$postModel->findByPk($id)){
            redirectMain();
        }
        $postData = post("PostForm");
        if(isset($postData)){
            $postModel->load($postData);
            if($postModel->validate() && $postModel->save()){
                redirectMain();
            }
        }
        renderEditPost($postModel,$user);
        break;
    case 'login':
        if(isset($user)){
            redirectMain();
        }
        $user = new LoginForm($db);
        $loginData = post('LoginForm');
        if(isset($loginData)){
            $user->load($loginData);
            if($user->validate()){
                if($user->find(['login LIKE :login','password LIKE :password'],[':login'=>$user->getAttribute('login'),':password'=>md5($user->getAttribute('password'))])){
                    $_pk = $user->getAttribute($user->getPk());
                    $user = new User($db);
                    $user->findByPk($_pk);
                    $_SESSION['user'] = $user->getAttributes();
                    redirectMain();
                } else {
                    $user->addError('summary','Login or Password invalid');
                }
            }
        }
        renderLogin($user);
        break;
    case 'logout':
        if(isset($user)){
            unset($_SESSION['user']);
        }
        redirectMain();
        break;
    default:
        $postModel = new Post($db);
        $postData = post("PostForm");
        if(isset($postData)){
            $postModel->load($postData);
            if($postModel->validate() && $postModel->save()){
                $_SESSION['addPostSuccess'] = "Ваше сообщение было добавлено. После модерации оно будет доступно для просмотра.";
                $postModel = new Post($db);
            }
        }
        renderPosts($postModel,$postModel->getPosts(isset($user)),$user);
        break;
}
?>