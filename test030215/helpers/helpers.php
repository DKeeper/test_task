<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:35
 * Created by JetBrains PhpStorm.
 */

/**
 * @param $str
 * @param $config
 * @return null|string
 */
function validateRequire($str,$config){
    return !empty($str) ? null : i18n::t($config['message']);
}

/**
 * @param $path
 * @param $config
 * @return null|string
 */
function validateFile($path,$config){
    $valid = true;
    if(!is_file($path)){
        $valid = false;
        $config['message'] = i18n::t('Wrong file');
    } else {
        try{
            $img = new Imagick($path);
            $identity = $img->identifyImage();
            $type = str_replace("image/","",$identity['mimetype']);
            if(!in_array($type,$config['allowedType'])){
                $valid = false;
            }
        }
        catch (ImagickException $e){
            return i18n::t("File is not image");
        }
    }
    return $valid ? null : i18n::t($config['message']);
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
    return $valid ? null : i18n::t($config['message'].$config[1]);
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
    return $valid ? null : i18n::t($config['message']);
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

/**
 * @param LoginForm $user
 */
function renderProfile($user){
    echo viewPhpFile(getViewPath().'profile.php',[
        'user' => $user,
    ]);
}

/**
 * @param LoginForm $user
 */
function renderLogin($user){
    echo viewPhpFile(getViewPath().'login.php',[
        'user' => $user,
    ]);
}

/**
 * @param User $user
 */
function renderRegistration($user){
    echo viewPhpFile(getViewPath().'registration.php',[
        'user' => $user,
    ]);
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
        $this->pdoStatement->execute($params);
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

class i18n{
    public static $language = 'en';

    public static $source;

    public static function init($config){
        if(isset($config['language'])){
            self::$language=$config['language'];
        }
        $sourceFile = dirname(__DIR__)."/i18n/".self::$language."/source.php";
        if(is_file($sourceFile)){
            self::$source = require_once($sourceFile);
        }
    }

    /**
     * @param string $str
     * @return string
     */
    public static function t($str){
        if(isset(self::$source[$str]))
            return self::$source[$str];
        return $str;
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
        foreach($this->rules as $attr => $rules){
            foreach($rules as $num => $rule){
                $this->rules[$attr][$num]['message'] = i18n::t($this->rules[$attr][$num]['message']);
            }
        }
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
        if(isset($this->attributes[$name])){
            $this->attributes[$name] = $value;
        }
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
        foreach($fields as $name){
            $params[':'.$name] = $this->getAttribute($name);
        }
        $sql .= " (".implode(' , ',$fields).") VALUES (".implode(',',array_keys($params)).")";
        if( ($result = $this->_db->query($sql,$params) !== true) ) {
            $this->attributes[$this->_pk] = $this->_db->lastInsertId();
        }
        return $result;
    }

    public function update(){
        $sql = "UPDATE ".$this->getTableName()." SET ";
        $pkI = array_search($this->_pk,$this->fields);
        $fields = array_splice($this->fields,$pkI,1);
        $params = []; $ins=[];
        foreach($fields as $name){
            $params[':'.$name] = $this->getAttribute($name);
            $ins[] = $name." = :".$name;
        }
        $sql .= implode(" , ",$ins)." WHERE ".$this->_pk." = ".$this->attributes[$this->_pk];
        return $this->_db->query($sql,$params);
    }

    public function save(){
        if(!isset($this->attributes[$this->_pk])){
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function load($data=[]){
        foreach($data as $name => $value){
            if(in_array($name,$this->fields)){
                $this->attributes[$name] = $value;
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

class User extends Model{
    protected $tableName='user';

    protected $fields=['id','login','password','email','phone','first_name','last_name','avatar'];

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
        'email' => [
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
                'message' => 'Invalid email',
            ]
        ],
        'phone' => [
            [
                'type' => 'regExp',
                'pattern' => '/^\+\d{10,10}$/',
                'message' => 'Invalid format. Only +1234567890',
            ]
        ],
        'first_name' => [
            ['type'=>'length','message'=>'Max length - ','max',15],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'last_name' => [
            ['type'=>'length','message'=>'Max length - ','max',15],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'avatar' => [
            [
                'type' => 'file',
                'allowedType' => ['jpeg','gif','png'],
                'message' => 'Allowed extension: jpg,gif,png',
            ]
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