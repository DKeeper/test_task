<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 20.04.15
 * @time 8:22
 * Created by JetBrains PhpStorm.
 */

/** @var $dirPath string Путь до папки для сканирования */
$dirPath = dirname(__FILE__).DIRECTORY_SEPARATOR."datafiles";

/** @var $findPattern string Шаблон поиска файлов */
$findPattern = '/^[a-zA-Z0-9\s]+?.ixt$/';

/** @var $matchFiles array() Массив для хранения найденных файлов */
$matchFiles = array();

/*
 * 1. Проверяем путь - папка это или нет
 * 2. Пробуем ее открыть, если не удается - выбрасываем исключение
 * 3. Считываем файлы из папки и в случае совпадения шаблона - сохраняем имя файла в массив
 */
if(dir($dirPath)){
    $handle = opendir($dirPath);
    if ($handle === false) {
        throw new Exception('Unable to open directory: ' . $dirPath);
    }
    while (($file = readdir($handle)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if(preg_match($findPattern,$file)) {
            $matchFiles[] = $file;
        }
    }
    closedir($handle);
}

/*
 * Сортируем массив по возрастанию
 */
asort($matchFiles);

/*
 * Выводим результат на экран
 */
if(!empty($matchFiles)){
    foreach($matchFiles as $fileName){
        echo "<p>$fileName</p>";
    }
} else {
    echo "<p>Файлов, подходящих под шаблон [$findPattern] не найдено</p>";
}