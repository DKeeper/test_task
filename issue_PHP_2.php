<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 12.08.14
 * @time 1:26
 * Created by JetBrains PhpStorm.
 */

date_default_timezone_set('Europe/Moscow');

class DateTimeChecker {
    public $formats = [];

    public $err;

    public function __construct($formats=[]){
        if(isset($formats) && is_array($formats)){
            $this->formats = $formats;
        }
    }

    public function check($date='',$formats=null){
        $this->err =[];

        if(empty($date)) $this->err[] = 'Empty string with date';

        if(!isset($formats) && empty($this->formats)) $this->err[] = 'Not specified format string';

        if(!empty($this->err)) return $this->err;

        $patterns = $this->formats;

        if(isset($formats)){
            if(is_array($formats)) $patterns = $formats;
            elseif(is_string($formats)) $patterns = [$formats];
        }

        foreach($patterns as $pattern){
            $_tempstmp = strtotime($date);
            if($_tempstmp===false) {
                $this->err['input'] = "Invalid format in input string";
                break;
            }
            $_tempdate = date($pattern,$_tempstmp);
            if($_tempdate !== $date) $this->err[$pattern] = "Invalidate format";
        }

        return $this->err;
    }
}

$checker = new DateTimeChecker([
    'd.m.Y','H.i','d-m-Y'
]);
$res1 = $checker->check('21.12.2013');
$res2 = $checker->check('12-01-2013');
$res3 = $checker->check('2013-05-29');
$res4 = $checker->check('01.23');
$res5 = $checker->check('01/01/2003','d/m/Y');
$res6 = $checker->check('01 01 2015',['d/m/Y','d m Y']);
$res7 = $checker->check('01-01-2015',['d/m/Y','d m Y','m-d-Y']);