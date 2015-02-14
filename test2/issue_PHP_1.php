<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 11.08.14
 * @time 23:54
 * Created by JetBrains PhpStorm.
 */

date_default_timezone_set('Europe/Moscow');

/**
 * @property $name
 * @property $ext
 * @property $createdTime
 */
class Document implements arrayaccess {
    public $name;
    protected $ext;
    private $createdTime;

    public function __construct(){

    }

    public function __get($name){
        if(property_exists($this,$name)){
            return $this->$name;
        }
        return null;
    }

    public function __set($name,$value){
        if(property_exists($this,$name)){
            $this->$name = $value;
        }
    }

    public function offsetSet($offset, $value) {
        if (!is_null($offset) && property_exists($this,$offset)) {
            $this->$offset = $value;
        }
    }

    public function offsetExists($offset) {
        return property_exists($this,$offset);
    }

    public function offsetUnset($offset) {
        if(property_exists($this,$offset)) $this->$offset = null;
    }

    public function offsetGet($offset) {
        return property_exists($this,$offset) ? $this->$offset : null;
    }

    public function save(){
        return true;
    }
}

$_doc = new Document();
$_doc['name'] = 'Test';
$_doc['ext'] = 'txt';
$_doc['createdTime'] = '2014-06-01';
$_doc->save();