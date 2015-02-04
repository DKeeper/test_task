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
 * @return int
 */
function validate($str,$config){
    $valid = true;
    if(!preg_match($config['pattern'],$str,$matches))
        $valid = false;
    return $valid ? null : $config['message'];
}

function viewPhpFile($_file_, $_params_ = [])
{
    ob_start();
    ob_implicit_flush(false);
    extract($_params_, EXTR_OVERWRITE);
    require($_file_);

    return ob_get_clean();
}