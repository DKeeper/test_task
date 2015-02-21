<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 17.02.15
 * @time 5:49
 * Created by JetBrains PhpStorm.
 *
 * Обновленный layout, в соответствии с тестовым заданием. Верстка адаптивная, но есть проблемы при показе на маленьких экранах.
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\ZebraAsset;
use kartik\icons\Icon;

/* @var $this \yii\web\View */
/* @var $content string */

Icon::map($this, Icon::WHHG);
ZebraAsset::register($this);
$baseUrl = Url::base(true);
$script = <<<JS
    jQuery(window).on('resize',fixMenuBackground);
    function fixMenuBackground(){
        if(jQuery(".navbar-toggle").is(":visible")){
            jQuery(".navigation").css("background-image","none");
            jQuery(".header-wrap").css("background-image","none");
        } else {
            jQuery(".navigation").css("background-image","url('$baseUrl/source/header_logo_background_bottom.png')");
            jQuery(".header-wrap").css("background-image","url('$baseUrl/source/header_logo_background_top.png')");
        }
    }
    // first check after load
    fixMenuBackground();
JS;

$this->registerJs($script,$this::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container">
    <header>
        <div class="header-wrap">
            <div class="row">
                <div class="col-md-5 col-md-offset-7 col-sm-5 col-sm-offset-7 col-xs-12">
                    <div class="row show-grid text-center header-signin">
                        <div class="col-md-4 col-xs-5">
                            <?= Icon::show('lock', ['class'=>'header-signin-icon'], Icon::WHHG) ?>
                            <?= Html::a(Yii::t('view','private office'),'#',['class'=>'header-signin-link']) ?>
                        </div>
                        <div class="col-md-8 col-xs-7 search-cell">
                            <?= Html::input('text','search','',['class'=>'header-search-input','placeholder'=>Yii::t('view','Site search')]) ?>
                            <?= Html::button(Icon::show('search', [], Icon::WHHG),['class'=>'btn-search']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row header-main">
                <div class="col-md-7 col-xs-12">
                    <div class="row header-main-row">
                        <div class="col-md-7 col-sm-6 col-xs-6 header-main-logo"></div>
                        <div class="col-md-5 col-sm-6 col-xs-6">
                            <span class="thesis thesis-1">Городские номера</span>
                            <span class="thesis thesis-2">Номер 8800</span>
                            <span class="thesis thesis-3">IP PBX</span>
                            <span class="thesis thesis-4">IP телефония</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-xs-12">
                    <div class="row header-main-row">
                        <div class="col-md-9 col-sm-6 col-xs-9">
                            <div class="row header-phone-wrap">
                                <div class="col-md-12 text-right"><span class="header-phone">8-800-100-17-50</span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right"><?= Html::a(Yii::t('view','contacts'),'#',['class'=>'header-contact-link']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-3 text-center language-wrap">
                            <?= Html::checkbox('language',true,['id'=>'language','class'=>'header-language-selector']) ?>
                            <?= Html::label('','language',['class'=>'language-selector']) ?>
                            <?= Html::label('','',['class'=>'language-name']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        NavBar::begin([
            'options' => [
                'class' => 'navbar-inverse navigation',
                'id' => 'navigation'
            ],
        ]);
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav header-menu'],
            'items' => [
                ['label' => Icon::show('home', [], Icon::WHHG), 'url' => ['/site/index'],'linkOptions'=>['class'=>'header-menu-home']],
                ['label' => Yii::t('view','Sign in'), 'url' => ['/site/partner'],'linkOptions'=>['class'=>'header-menu-text']],
                ['label' => Yii::t('view','Tariff'), 'url' => ['/site/tariff'],'linkOptions'=>['class'=>'header-menu-text']],
                ['label' => Yii::t('view','Services'), 'url' => ['/site/services'],'linkOptions'=>['class'=>'header-menu-text']],
                ['label' => Yii::t('view','Offers'), 'url' => ['/site/offers'],'linkOptions'=>['class'=>'header-menu-text']],
                ['label' => Yii::t('view','Equipment'), 'url' => ['/site/equipment'],'linkOptions'=>['class'=>'header-menu-text']],
            ],
        ]);
        NavBar::end();
        ?>
    </header>
    <div class="row"><div class="col-md-12"><div class="content-wrap"><?= $content ?></div></div></div>
    <footer>
        <div class="footer-wrap">
            <div class="row">
                <div class="col-md-2 col-md-offset-1 col-xs-offset-1 col-xs-5">
                    <h4><?= Html::a(Yii::t('view','About us'),'#',['class'=>'footer-list-header']) ?></h4>
                    <ul class="footer-list">
                        <li><?= Html::a(Yii::t('view','History'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Team'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','We recommend'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Events'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','News'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Press about us'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Pressroom'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Banking details'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Jobs'),'#') ?></li>
                    </ul>
                </div>
                <div class="col-md-2 col-xs-5">
                    <h4><?= Html::a(Yii::t('view','Documents'),'#',['class'=>'footer-list-header']) ?></h4>
                    <ul class="footer-list">
                        <li><?= Html::a(Yii::t('view','Licenses'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Contracts'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Letters'),'#') ?></li>
                    </ul>
                </div>
                <div class="col-md-2 col-xs-5">
                    <h4><?= Html::a(Yii::t('view','Operators'),'#',['class'=>'footer-list-header']) ?></h4>
                    <ul class="footer-list">
                        <li><?= Html::a(Yii::t('view','Buying traffic'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','Sale traffic'),'#') ?></li>
                    </ul>
                </div>
                <div class="col-md-2 col-xs-5">
                    <h4><?= Html::a(Yii::t('view','Questions and Answers'),'#',['class'=>'footer-list-header']) ?></h4>
                    <ul class="footer-list">
                        <li><?= Html::a(Yii::t('view','How to call'),'#') ?></li>
                        <li><?= Html::a(Yii::t('view','How to calculate the cost of the call'),'#') ?></li>
                    </ul>
                </div>
                <div class="col-md-2 col-xs-offset-1 col-xs-5">
                    <h4><?= Html::a(Yii::t('view','Contacts'),'#',['class'=>'footer-list-header']) ?></h4>
                    <ul class="footer-list">
                        <li><?= Html::a(Yii::t('view','Feedback'),'#') ?></li>
                    </ul>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-md-8 col-md-offset-1 col-xs-offset-1 col-xs-7">
                    <p class="copyright">&copy; 2001-<?= date('Y') ?> <?= Yii::t('view','Zebra telecom')?>, <?= Yii::t('view','Tel:')?> 8-800-100-17-50</p>
                </div>
                <div class="col-md-2 col-xs-3">
                    <p class="text-right">
                        <?= Html::a(Html::img(Url::base(true).'/source/social-rss.png'),'#') ?>
                        <?= Html::a(Html::img(Url::base(true).'/source/social-fb.png'),'#') ?>
                        <?= Html::a(Html::img(Url::base(true).'/source/social-tw.png'),'#') ?>
                        <?= Html::a(Html::img(Url::base(true).'/source/social-vk.png'),'#') ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>