<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 12.08.14
 * @time 11:13
 * Created by JetBrains PhpStorm.
 */

date_default_timezone_set('Europe/Moscow');

class DBquery {
    private $_connection;

    private $_query;

    public function __construct($config){
        $this->_connection = mysql_connect($config['host'], $config['user'], $config['pass'])
            or die("Could not connect : " . mysql_error());

        mysql_select_db($config['dbname']) or die("Could not select database");
    }

    public function __destruct(){
        mysql_close($this->_connection);
    }

    /**
     * @param $sql string
     * @return array
     */
    public function getAssocData($sql){
        $this->_query = mysql_query($sql) or die("Query failed : " . mysql_error());

        $_res = [];
        while ($row = mysql_fetch_assoc($this->_query)) {
            $_res[] = $row;
        }

        mysql_free_result($this->_query);

        return $_res;
    }

    public function query($sql){
        $this->_query = mysql_query($sql) or die("Query [{$sql}] failed : " . mysql_error());

        if($this->_query===true){
            return $this->_query;
        }

        $_res = mysql_fetch_row ($this->_query);

        mysql_free_result($this->_query);

        return $_res;
    }
}

class Application {
    private $_initSQL = "
        CREATE TABLE IF NOT EXISTS structure (
        id int(11) NOT NULL AUTO_INCREMENT,
        name text NOT NULL,
        size int(11) DEFAULT NULL,
        type text DEFAULT NULL,
        modified_at int(11),
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Структура папок';
    ";

    private $_db;

    private $_settings;

    private $_structure;

    public function __construct($config){
        if(!isset($config['db'])){
            die("Not specified data of database connection");
        }
        $this->_settings = $config;
        $this->_db = new DBquery($config['db']);
    }

    protected function init(){
        $checkStructureTable = $this->_db->query("SHOW TABLES LIKE 'structure'");
        if($checkStructureTable===false){
            $createTab = $this->_db->query($this->_initSQL);
            if($createTab===true){
                $this->_structure = $this->updateStructure();
            }
        } else {
            if(isset($_POST) && isset($_POST['refresh'])){
                $this->_structure = $this->updateStructure();
            } else {
                $this->_structure = $this->getStucture();
            }
        }
    }

    protected function parseStructure($path){
        $data = [];
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if(is_file($path . $file)){
                        $data[] = [
                            'name' => $file,
                            'size' => filesize($path . $file),
                            'type' => pathinfo($path . $file, PATHINFO_EXTENSION),
                            'modified_at' => filemtime($path . $file)
                        ];
                    } else {
                        $data[] = [
                            'name' => $file,
                            'modified_at' => filemtime($path . $file)
                        ];
                    }
                }
                closedir($dh);
            }
        }
        return $data;
    }

    public function updateStructure(){
        $path = $_SERVER['DOCUMENT_ROOT'];
        $this->truncateStructure();
        $data = $this->parseStructure($path);
        foreach($data as $row){
            $sql = "INSERT INTO `structure`";
            $_f = "(";
            $_v = "VALUES (";
            foreach($row as $name => $value){
                $_f .= "`{$name}`,";
                $_v .= "'{$value}',";
            }
            $_f = rtrim($_f,',').")";
            $_v = rtrim($_v,',').")";
            $sql .= " {$_f} {$_v}";
            $this->_db->query($sql);
        }
        return $data;
    }

    public function getStucture(){
        return $this->_db->getAssocData("SELECT * FROM structure");
    }

    protected function truncateStructure(){
        return $this->_db->query("TRUNCATE TABLE structure");
    }

    protected function render(){
        if(empty($this->_structure)){
            echo "No data for show";
        } else {
            echo "<table><tr><td>Name</td><td>Size</td><td>Type</td><td>Modified date</td></tr>";
            foreach($this->_structure as $row){
                $row['modified_at'] = date("H:m:i d.m.Y",$row['modified_at']);
                $row['size'] = isset($row['size'])?$row['size']:'DIR';
                $row['type'] = isset($row['type'])?$row['type']:'';
                echo "<tr><td>{$row['name']}</td><td>{$row['size']}</td><td>{$row['type']}</td><td>{$row['modified_at']}</td></tr>";
            }
            echo "</table>";
            echo "<form method='POST'><input type='hidden' name='refresh'><button>Обновить</button><form>";
        }
    }

    public function run(){
        $this->init();
        $this->render();
    }
}

$app = new Application([
    'db'=>[
        'host'=>'localhost',
        'user'=>'root',
        'pass'=>'G56rnkA9m',
        'dbname'=>'test'
    ]
]);
$app->run();