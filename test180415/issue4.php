<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 20.04.15
 * @time 8:54
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

/**
 * Массивы для замены названий месяцев на числовое значение
 * @var $ru_month array
 * @var $ru_month array
 */
$ru_month = array( ' янв ', ' фев ', ' мар ', ' апр ', ' май ', ' июн ', ' июл ', ' авг ', ' сен ', ' окт ', ' ноя ', ' дек ' );
$en_month = array( '.01.', '.02.', '.03.', '.04.', '.05.', '.06.', '.07.', '.08.', '.09.', '.10.', '.11.', '.12.');

/** @var $tableName string Название таблицы для хранения данных */
$tableName = 't180415_bills_ru_events';

/** @var $siteUrl string Адрес сайта для парсинга */
$siteUrl = 'http://www.bills.ru/';

/** @var $newsRowCssClass string Класс строк, содержащих нужную информацию */
$newsRowCssClass = '.bizon_api_news_row';

/** @var $maxRows integer Максимальное количество обрабатываемых строк c новостями*/
$maxRows = 10;

/** @var $loader \Zend\Loader\StandardAutoloader Экземпляр загрузчика */
$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));

/** Регистрируем автозагрузчик */
$loader->register();

/** @var $adapter \Zend\Db\Adapter\Adapter Адаптер для работы с БД */
$adapter = new Zend\Db\Adapter\Adapter($driverConfig);

/** @var $html string Содержимое сайта в виде строки */
$html = file_get_contents($siteUrl);

/** Меняем кодировку содержимого */
$html = iconv('Windows-1251','UTF-8',$html);

/** Меняем кодировку непосредственно у сайта */
$html = str_replace('charset=windows-1251','charset=utf-8',$html);

/** @var $dom \Zend\Dom\Query Загружаем документ в компонент для последующего поиска по селектору css*/
$dom = new \Zend\Dom\Query($html,'UTF-8');

/** @var $results \Zend\Dom\NodeList Результат поиска по селектору css*/
$results = $dom->execute($newsRowCssClass);

/** @var $info array Массив для хранения итоговой информации */
$info = array();

/** Если что-то нашли, производим разбор данных */
if(count($results)>0){
    foreach($results as $i => $result){
        /**
         * $result содержит код следующего вида:
         * <td class="news_date">21 апр        </td>
         * <td>
         * <a href="http://www.bills.ru/news/?bid=138004">Путин подписал закон, направленный на повышение капитализации кредитных организаций</a>
         * <span class="bizon_api_news_original_date">(от 21 апр)</span>
         * </td>
         * @var $result DOMElement
         */
        /** Достигли предела анализируемых строк - выходим из цикла */
        if($i==$maxRows) break;
        /** @var $_insertData array Данные, полученные при разборе одной строки */
        $_insertData = array("date"=>null,"url"=>null,"title"=>null);
        foreach($result->childNodes as $num => $node){
            switch($num){
                /** Парсинг дат в первой колонке td */
                case 0:
                    /** Дата может быть указана как с годом, так и без */
                    if(preg_match('/.+?(\d+?\s.{6}\s\d{4}).+/s',$node->textContent))
                        $_insertData["date"] = preg_replace('/.+?(\d+?\s.{6}\s\d{4}).+/s','$1',$node->textContent);
                    else
                        $_insertData["date"] = preg_replace('/.+?(\d+?\s.{6}).+/s','$1',$node->textContent) . " " . date("Y");
                    /** Заменяем названия месяцев на числовые значения */
                    $_insertData["date"] = str_replace($ru_month,$en_month,$_insertData["date"]);
                    $_date = explode('.',$_insertData["date"]);
                    $_insertData["date"] = $_date[2]."-".$_date[1]."-".$_date[0]." 00-00-00";
                    break;
                /** Парсинг ссылки во второй колонке td */
                case 2:
                    foreach($node->childNodes as $nodeNum => $child){
                        /** Находим ссылку и извлекаем нужные данные - саму новость и линк на нее */
                        if($child instanceof DOMElement && $child->tagName == "a"){
                            $_insertData["title"] = $child->textContent;
                            $_insertData["url"] = $child->getAttribute("href");
                            break;
                        }
                    }
                    break;
            }

        }
        /** Записываем промежуточные результаты в итоговый массив */
        $info[] = $_insertData;
    }
}

if(!empty($info)){
    /** @var $validator \Zend\Validator\Db\NoRecordExists Валидатор для проверки на уникальность записи */
    $validator = new Zend\Validator\Db\NoRecordExists(
        array(
            'table'   => $tableName,
            'field'   => 'url',
            'adapter' => $adapter
        )
    );
    /** @var $infoTable \Zend\Db\TableGateway\TableGateway Объект для работы с таблицей БД */
    $infoTable = new \Zend\Db\TableGateway\TableGateway($tableName, $adapter);
    foreach($info as $insertData){
        /**
         * Если записи с таким URL не найдено - записываем новое значение
         */
        if ($validator->isValid($insertData['url'])) {
            if ( $infoTable->insert($insertData) )
                echo "Added row [ID: $infoTable->lastInsertValue]<br/>";
        } else {
            foreach ($validator->getMessages() as $message) {
                echo "$message [URL: ".$insertData['url']."]<br/>";
            }
        }
    }
}