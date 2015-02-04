<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:07
 * Created by JetBrains PhpStorm.
 */

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

    public function query($sql,$params=[],$type=self::TYPE_FETCH_ALL){
        $this->pdoStatement = $this->db->prepare($sql);
        $this->pdoStatement->execute($params);
        if($type==self::TYPE_FETCH_ALL)
            return $this->pdoStatement->fetchAll($this->fetchStyle);
        else
            return $this->pdoStatement->fetch($this->fetchStyle);
    }

    public function findOne($sql,$params=[]){
        return $this->query($sql,$params,self::TYPE_FETCH_ONE);
    }
}