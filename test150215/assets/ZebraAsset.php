<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 17.02.15
 * @time 5:53
 * Created by JetBrains PhpStorm.
 */
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class ZebraAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/zebra.css',
    ];
    public $js = [
        'http://html5shiv.googlecode.com/svn/trunk/html5.js'
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
        'condition' => 'lt IE 9',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
