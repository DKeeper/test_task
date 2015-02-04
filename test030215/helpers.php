<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:35
 * Created by JetBrains PhpStorm.
 */


function validateFile($path,$config){
    $valid = true;
    if(!is_file($path)){
        $valid = false;
        $config['message'] = 'Wrong file';
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
            return "File is not image";
        }
    }
    return $valid ? null : $config['message'];
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

/**
 * @param array $res
 */
function renderProfile($res){
    echo viewPhpFile(__DIR__.'/view/profile.php',[
        'user' => $res,
    ]);
}

/**
 * @param string $login
 * @param string $pass
 * @param array $err
 * @param array $rules
 */
function renderLogin($login='',$pass='',$err=[],$rules=[]){
    echo viewPhpFile(__DIR__.'/view/login.php',[
        'login' => $login,
        'pass' => $pass,
        'err' => $err,
        'rules' => $rules,
    ]);
}

/**
 * @param array $user
 * @param array $err
 * @param array $rules
 */
function renderRegistration($user=[],$err=[],$rules=[]){
    echo viewPhpFile(__DIR__.'/view/registration.php',[
        'user' => $user,
        'err' => $err,
        'rules' => $rules,
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
}