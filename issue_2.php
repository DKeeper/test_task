<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 24.07.14
 * @time 13:40
 * Created by JetBrains PhpStorm.
 */
if(!empty($_POST)){
    $family = new Family($_POST);
    $family->crossing();
    $_POST['log'] = file_get_contents($family->log);
    unset($family);
    begin($_POST);
} else {
    begin();
}

function begin($param=[]){
    echo "
    <html>
        <head>
            <title>Test task</title>
        </head>
        <body>
            <form method='post'>
                <label for='adult'>Взрослых</label>
                <input id='adults' name='adults' type='text' value='".(isset($param['adults'])?$param['adults']:0)."'>
                <label for='children'>Детей</label>
                <input id='children' name='children' type='text' value='".(isset($param['children'])?$param['children']:0)."'>
                <input type='submit' value='Посчитать'>
            </form>
            ".
            (isset($param['log'])?"<div class='log'>".nl2br($param['log'])."<div>":"")
            ."
        </body>
    </html>
    ";
}

// -----------------------------------------------------------
/**
 * @property $log resource
 */
class Object {
    protected $log;

    public function __construct($params=[]){
        foreach($params as $name => $value ){
            if(property_exists(get_class($this),$name)){
                $this->$name = $value;
            }
        }
        if(!isset($this->log)){
            $this->log = dirname(__FILE__).'/'.uniqid("").".log";
        }
    }

    public function __get($name){
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }

    public function __set($name,$value){
        if(isset($this->$name)){
            $this->$name = $value;
        }
    }

    public function trace($message){
        $h = fopen($this->log,'a+');
        fwrite($h,$message."\r\n");
        fclose($h);
    }
}

/**
 * @property $name string
 * @property $type string
 * @property $position string
 */
class Man extends Object{
    protected $name;
    protected $type;
    protected $position;
    protected static $_count = 0;

    public function __construct($params=[]){
        if(!isset($params['name'])){
            $params['name'] = get_class($this).'_'.self::$_count;
            self::$_count++;
        }
        if(!isset($params['position'])){
            $params['position'] = "left bank";
        }
        parent::__construct($params);
    }

    public function changePosition(){
        $message = $this->name." move from ".$this->position;
        if($this->position == "left bank"){
            $this->position = "right bank";
        } else {
            $this->position = "left bank";
        }
        $this->trace($message." to ".$this->position);
    }
}
class Adult extends Man{
    public function __construct($params=[]){
        $params['type'] = 'adult';
        parent::__construct($params);
    }
}

class Child extends Man{
    public function __construct($params=[]){
        $params['type'] = 'child';
        parent::__construct($params);
    }
}

/**
 * @property $adults Adult[]
 * @property $children Child[]
 */
class Family extends Object{
    protected $adults;
    protected $children;

    const ADULTS_COUNT = 2;
    const CHILDREN_COUNT = 2;

    /**
     * @return bool
     */
    protected function _check(){
        if(count($this->children)<2){
            $this->trace("Number of children can not be 0 or 1");
            return false;
        }
        return true;
    }

    public function __destruct(){
        foreach($this->adults as $a_){
            unset($a_);
        }
        foreach($this->children as $c_){
            unset($c_);
        }
    }

    public function __construct($params=[]){
        if(!isset($params['adults'])){
            $params['adults'] = self::ADULTS_COUNT;
        } else {
            $params['adults'] = intval($params['adults']);
        }
        if(!isset($params['children'])){
            $params['children'] = self::CHILDREN_COUNT;
        } else {
            $params['children'] = intval($params['children']);
        }
        parent::__construct($params);
        $this->trace("Create Fisherman");
        $_a = [new Adult(['name'=>'Fisherman','log'=>$this->log])];
        for($i=0;$i<$this->adults;$i++){
            $_ = new Adult(['log'=>$this->log]);
            $this->trace("Create ".$_->name);
            $_a[] = $_;
        }
        $this->adults = $_a;
        $_c = [];
        for($i=0;$i<$this->children;$i++){
            $_ = new Child(['log'=>$this->log]);
            $this->trace("Create ".$_->name);
            $_c[] = $_;
        }
        unset($_);
        $this->children = $_c;
    }

    public function crossing(){
        $this->trace("Check group");
        if(!$this->_check()) return;
        $this->trace("Start crossing");
        $_countAdultInLeftBank = count($this->adults);
        $_countAdultInRightBank = 0;
        $_countChildrenInLeftBank = count($this->children);
        $_countChildrenInRightBank = 0;
        // Move adults
        do{
            $this->trace("<strong>Left [a:{$_countAdultInLeftBank},c:{$_countChildrenInLeftBank}], Right [a:{$_countAdultInRightBank},c:{$_countChildrenInRightBank}]</strong>");
            /** @var $child Child*/
            /** @var $adult Adult*/
            $this->trace("Step - move 2 children to right bank");
            $_ = 0;
            foreach($this->children as $child){
                if($child->position == "left bank"){
                    $child->changePosition();
                    $_countChildrenInLeftBank--;
                    $_++;
                }
                if($_==2) {
                    $_countChildrenInRightBank += $_;
                    break;
                }
            }
            $this->trace("Step - move 1 children to left bank");
            foreach($this->children as $child){
                if($child->position == "right bank"){
                    $child->changePosition();
                    $_countChildrenInLeftBank++;
                    $_countChildrenInRightBank--;
                    break;
                }
            }
            $this->trace("Step - move 1 adult to right bank");
            foreach($this->adults as $adult){
                if($adult->position == "left bank"){
                    $adult->changePosition();
                    $_countAdultInLeftBank--;
                    $_countAdultInRightBank++;
                    break;
                }
            }
            $this->trace("Step - move 1 children to left bank");
            foreach($this->children as $child){
                if($child->position == "right bank"){
                    $child->changePosition();
                    $_countChildrenInLeftBank++;
                    $_countChildrenInRightBank--;
                    break;
                }
            }
        } while ($_countAdultInLeftBank>0);
        // Move children
        do{
            $this->trace("<strong>Left [a:{$_countAdultInLeftBank},c:{$_countChildrenInLeftBank}], Right [a:{$_countAdultInRightBank},c:{$_countChildrenInRightBank}]</strong>");
            /** @var $child Child*/
            /** @var $adult Adult*/
            $this->trace("Step - move 2 children to right bank");
            $_ = 0;
            foreach($this->children as $child){
                if($child->position == "left bank"){
                    $child->changePosition();
                    $_countChildrenInLeftBank--;
                    $_++;
                }
                if($_==2) {
                    $_countChildrenInRightBank += $_;
                    break;
                }
            }
            if($_countChildrenInLeftBank==0) break;
            $this->trace("Step - move 1 children to left bank");
            foreach($this->children as $child){
                if($child->position == "right bank"){
                    $child->changePosition();
                    $_countChildrenInLeftBank++;
                    $_countChildrenInRightBank--;
                    break;
                }
            }
        } while ($_countChildrenInLeftBank>0);
        $this->trace("<strong>Left [a:{$_countAdultInLeftBank},c:{$_countChildrenInLeftBank}], Right [a:{$_countAdultInRightBank},c:{$_countChildrenInRightBank}]</strong>");
        $this->trace("Finish crossing");
    }
}