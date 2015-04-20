<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 20.04.15
 * @time 11:08
 * Created by JetBrains PhpStorm.
 */

/** Подключаем стандартный автозагрузчик */
require_once 'Zend/Loader/StandardAutoloader.php';

/** @var $driverConfig array Конфигурация соединения с БД */
$driverConfig = array(
    'driver' => 'Pdo_Mysql',
    'dsn' => 'mysql:host=localhost;dbname=test',
    'username' => 'test',
    'password' => 'RerzSwuWPzKvxNNT'
);

/** @var $loader \Zend\Loader\StandardAutoloader Экземпляр загрузчика */
$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));

/** Регистрируем автозагрузчик */
$loader->register();

/** @var $adapter \Zend\Db\Adapter\Adapter Адаптер для работы с БД */
$adapter = new Zend\Db\Adapter\Adapter($driverConfig);

try{
    $initObj = new Init($adapter);
    $data = $initObj->get();
    var_dump($data);
}
catch (Exception $e){
    echo $e->getMessage()."<br>";
    echo $e->getFile()." (Line ".$e->getLine().")<br>";
}

final class Init{
    /**
     * @var string Имя таблицы в БД
     */
    private $_tableName = 't180415_test';

    /**
     * @var string Строка инициализации таблицы БД
     */
    private $_initSQL = "
      DROP TABLE IF EXISTS `:tb`;
      CREATE TABLE `:tb` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `script_name` varchar(25) NOT NULL,
        `start_time` int(11) NOT NULL,
        `end_time` int(11) NOT NULL,
        `result` enum('normal','illegal','failed','success') NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ";

    /**
     * @var array Список доступных значений для поля result
     */
    private $_resultList = array(
        'normal','illegal','failed','success'
    );

    /**
     * @var \Zend\Db\Adapter\Adapter Адаптер для работы с БД
     */
    private $_adapter;

    /**
     * При создании экземпляра класса заново создается и заполняется случайными значениями таблица
     * @param $adapter Адаптер для работы с БД
     * @param int $rows Количество генерируемых записей
     * @throws Exception
     */
    public function __construct($adapter,$rows=10){
        $_cn = 'Zend\Db\Adapter\Adapter';
        if(!($adapter instanceof $_cn)){
            throw new Exception("Adapter must be object of $_cn");
        }
        $this->_adapter = $adapter;
        $this->create();
        $this->fill($rows);
    }

    /**
     * Функция генерации исходной пустой таблицы
     */
    private function create(){
        $this->_adapter->query($this->_initSQL,array(':tb'=>$this->_tableName));
    }

    /**
     * @param int $rows Количество вставляемых записей
     */
    private function fill($rows){
        for(;$rows>0;$rows--){
            $_scriptName = $this->generate(20);
            $_start = mt_rand();
            $_end = mt_rand();
            $_result = $this->_resultList[mt_rand(0,count($this->_resultList)-1)];
            $sql = "INSERT INTO `:tb` (`script_name`, `start_time`, `end_time`, `result`)
            VALUES (:sn,:st,:en,:rs);";
            $this->_adapter->query($sql,array(
                    ':tb' => $this->_tableName,
                    ':sn' => $_scriptName,
                    ':st' => $_start,
                    ':en' => $_end,
                    ':rs' => $_result,
                )
            );
        }
    }

    /**
     * Функция для генерации случайной строчки определенной длины
     * @param int $length Длина генерируемой строки
     * @return string
     */
    private function generate ($length = 64) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i <= $length; $i++) {
            $result .= $characters[mt_rand (0, strlen ($characters) - 1)];
        }
        return $result;
    }

    /**
     * Метод возвращает массив найденных в БД значений, для которых поле
     * result имеет значения ('normal', 'success')
     * @return array
     */
    public function get(){
        $sql = "SELECT * FROM `:tb` WHERE `result` IN ('normal',  'success')";
        /** @var $result \Zend\Db\ResultSet\ResultSet */
        $result = $this->_adapter->query($sql,array(':tb'=>$this->_tableName));
        return $result->toArray();
    }
}