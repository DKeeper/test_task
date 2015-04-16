<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:35
 * Created by JetBrains PhpStorm.
 */

/**
 * @param string $name
 * @param null|mixed $default
 * @return null|mixed
 */
function post($name,$default=null){
    if(isset($_POST[$name])){
        return $_POST[$name];
    } else {
        return $default;
    }
}

/**
 * @param string $name
 * @param null|mixed $default
 * @return null|mixed
 */
function get($name,$default=null){
    if(isset($_GET[$name])){
        return $_GET[$name];
    } else {
        return $default;
    }
}

/**
 * @param $str
 * @param $config
 * @return null|string
 */
function validateRequire($str,$config){
    return !empty($str) ? null : $config['message'];
}

/**
 * @param $param
 * @param $config
 * @return null|string
 */
function validate($param,$config){
    $valid = true;
    switch($config[0]){
        case 'min':
            if(strlen($param)<$config[1]) $valid=false;
            break;
        case 'max':
            if(strlen($param)>$config[1]) $valid=false;
            break;
    }
    return $valid ? null : $config['message'].$config[1];
}

/**
 * @param string $str
 * @param array $config
 * @return null|string
 */
function validateRegExp($str,$config){
    $valid = true;
    if(!preg_match($config['pattern'],$str,$matches))
        $valid = false;
    return $valid ? null : $config['message'];
}

/**
 * @param string $_file_
 * @param array $_params_
 * @return string
 */
function viewPhpFile($_file_, $_params_ = [])
{
    ob_start();
    ob_implicit_flush(false);
    extract($_params_, EXTR_OVERWRITE);
    require($_file_);

    return ob_get_clean();
}

function getViewPath(){
    return(dirname(__DIR__).'/view/');
}

function renderPostForm($model,$user=null){
    echo viewPhpFile(getViewPath().'_form_post.php',[
        'model' => $model,
        'asAdmin' => isset($user),
    ]);
}

function renderEditPost($model,$user=null){
    echo viewPhpFile(getViewPath().'edit_post.php',[
        'model' => $model,
        'user' => $user,
    ]);
}

function renderPosts($model,$data=[],$user=null){
    echo viewPhpFile(getViewPath().'posts.php',[
        'user' => $user,
        'posts' => $data,
        'model' => $model,
    ]);
}

function renderLogin($user){
    echo viewPhpFile(getViewPath().'login.php',[
        'user' => $user,
    ]);
}

function redirect($url){
    header( "Refresh: 0; url=$url" );
}

function redirectMain(){
    redirect(TEST_APP_URL);
}

/********************************************************************************/
class DBwrapper {

    const TYPE_FETCH_ALL = 1;
    const TYPE_FETCH_ONE = 2;
    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var \PDOStatement
     */
    private $pdoStatement;

    public $lastResult = false;

    public $fetchStyle = PDO::FETCH_ASSOC;

    /**
     * @param $config array
     */
    public function init($config){
        try {
            $this->db = new PDO($config['dsn'], $config['user'], $config['password']);
        }
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function query($sql,$params=[]){
        $this->pdoStatement = $this->db->prepare($sql);
        $this->lastResult = $this->pdoStatement->execute($params);
        return $this->pdoStatement;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function findOne($sql,$params=[]){
        return $this->query($sql,$params)->fetch($this->fetchStyle);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function findAll($sql,$params=[]){
        return $this->query($sql,$params)->fetchAll($this->fetchStyle);
    }

    /**
     * @return string
     */
    public function lastInsertId(){
        return $this->db->lastInsertId();
    }
}

class Model {
    /** @var \DBwrapper */
    private $_db;

    private $_pk = 'id';

    private $_errors=[];

    protected $fields=[];

    protected $tableName='';

    protected $attributes=[];

    protected $rules=[];

    /**
     * @param $db \DBwrapper
     */
    public function __construct(&$db){
        $this->_db = $db;
    }

    public function getRules(){
        return $this->rules;
    }

    public function getErrors(){
        return $this->_errors;
    }

    public function addError($name,$value){
        $this->_errors[$name] = $value;
    }

    public function getPk(){
        return $this->_pk;
    }

    public function setPk($pk){
        $this->_pk = $pk;
    }

    public function getDb(){
        return $this->_db;
    }

    public function getTableName(){
        return $this->tableName;
    }

    public function getAttribute($name){
        if(isset($this->attributes[$name]))
            return $this->attributes[$name];
        return "";
    }

    public function getAttributes(){
        return $this->attributes;
    }

    public function setAttribute($name,$value=''){
        $this->attributes[$name] = $value;
    }

    public function find($conditions=[],$params=[]){
        $sql = "SELECT * FROM ".$this->getTableName();
        if(!empty($conditions) && is_array($conditions)){
            $where = implode(' AND ',$conditions);
            $sql .= " WHERE ".$where;
        }
        $this->attributes = $this->_db->findOne($sql,$params);
        return is_array($this->attributes);
    }

    public function findAll($conditions=[],$params=[]){
        $sql = "SELECT * FROM ".$this->getTableName();
        if(!empty($conditions) && is_array($conditions)){
            $where = implode(' AND ',$conditions);
            $sql .= " WHERE ".$where;
        }
        $_q = $this->_db->findAll($sql,$params);
        if(!empty($_q)){
            $className = get_called_class();
            foreach($_q as $i => $attr){
                /** @var $_m Model */
                $_m = new $className($this->_db);
                $_m->attributes = $attr;
                $_q[$i] = $_m;
            }
        }
        return $_q;
    }

    public function findByPk($pk){
        $conditions = [$this->getPk().' = '.$pk];
        return $this->find($conditions);
    }

    public function insert(){
        $sql = "INSERT INTO ".$this->getTableName();
        $pkI = array_search($this->_pk,$this->fields);
        $fields = $this->fields;
        array_splice($fields,$pkI,1);
        $params = [];
        foreach($fields as $i => $name){
            $_v = $this->getAttribute($name);
            if(!isset($_v) || empty($_v)){
                unset($fields[$i]);
            } else {
                $params[':'.$name] = $_v;
            }
        }
        $sql .= " (".implode(' , ',$fields).") VALUES (".implode(',',array_keys($params)).")";
        $this->_db->query($sql,$params);
        if( $this->_db->lastResult === true ) {
            $this->attributes[$this->_pk] = $this->_db->lastInsertId();
        }
        return $this->_db->lastResult;
    }

    public function update(){
        $sql = "UPDATE ".$this->getTableName()." SET ";
        $pkI = array_search($this->_pk,$this->fields);
        $fields = $this->fields;
        array_splice($fields,$pkI,1);
        $params = []; $ins=[];
        foreach($fields as $i => $name){
            $_v = $this->getAttribute($name);
            if(!isset($_v) || empty($_v)){
                unset($fields[$i]);
            } else {
                $params[':'.$name] = $_v;
                $ins[] = $name." = :".$name;
            }
        }
        $sql .= implode(" , ",$ins)." WHERE ".$this->_pk." = ".$this->attributes[$this->_pk];
        $this->_db->query($sql,$params);
        return $this->_db->lastResult;
    }

    public function save(){
        if(!isset($this->attributes[$this->_pk])){
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function delete(){
        $sql = "DELETE FROM ".$this->getTableName()." WHERE ".$this->_pk." = ".$this->getAttribute($this->_pk);
        $this->_db->query($sql);
        return $this->_db->lastResult;
    }

    public function load($data=[]){
        foreach($data as $name => $value){
            if(in_array($name,$this->fields)){
                $this->attributes[$name] = trim($value);
            }
        }
    }

    public function validate(){
        $this->_errors = [];
        foreach($this->rules as $attr => $rules){
            foreach($rules as $rule){
                $err = null;
                switch($rule['type']){
                    case 'required':
                        $err = validateRequire($this->attributes[$attr],$rule);
                        break;
                    case 'regExp':
                        $err = validateRegExp($this->attributes[$attr],$rule);
                        break;
                    case 'length':
                        $err = validate($this->attributes[$attr],$rule);
                        break;
                    case 'file':
                        $err = validateFile($this->attributes[$attr],$rule);
                        break;
                }
                if(!is_null($err)){
                    $this->_errors[$attr] = $err;
                    break;
                }
            }
        }
        return empty($this->_errors);
    }
}

class Post extends Model{
    protected $tableName='post';

    protected $fields=['id','parent','user_name','message','created_at','published'];

    protected $rules=[
        'user_name' => [
            ['type' => 'required', 'message'=>'User name required'],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'message' => [
            ['type' => 'required', 'message'=>'Message required'],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_-\s]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits, _, - and "space"',
            ],
        ],
    ];

    public function getPosts($asAdmin=false){
        $conditions = ['parent IS NULL'];
        if(!$asAdmin){
            $conditions[] = 'published = 1';
        }
        return $this->findAll($conditions);
    }

    public function getReplys($asAdmin=false){
        $conditions = ['parent = '.$this->getAttribute($this->getPk())];
        if(!$asAdmin){
            $conditions[] = 'published = 1';
        }
        return $this->findAll($conditions);
    }

    public function getParent(){
        $_m = new Post($this->getDb());
        $_p = $this->getAttribute("parent");
        if(isset($_p)){
            if($_m->findByPk($_p)){
                return $_m;
            }
        }
        return null;
    }
}
class User extends Model{
    protected $tableName='user';

    protected $fields=['id','login','password'];

    protected $rules=[
        'login' => [
            ['type' => 'required', 'message'=>'Login required'],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'password' => [
            ['type' => 'required', 'message'=>'Password required'],
            ['type'=>'length','message'=>'Min length - ','min',6],
        ],
    ];
}

class LoginForm extends User{
    protected $fields=['login','password'];

    protected $rules=[
        'login' => [
            ['type' => 'required', 'message'=>'Login required'],
        ],
        'password' => [
            ['type' => 'required', 'message'=>'Password required'],
            ['type'=>'length','message'=>'Min length - ','min',6],
        ],
    ];
}