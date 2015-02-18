<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'ru_RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UMAnIvGB_JOh4Ymqm3EgY-QtMGAK1LBh',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'baseUrl' => '/test150215/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '' => 'site/index',
                '<c:(gii)>' => '<c>',
                '<c:\w+>' => '<c>/index',
                '<c:\w+>/<a:\w+>' => '<c>/<a>',
                '<c:\w+>/<a:\w+>/<id:\d+>' => '<c>/<a>',
                '<m:.+>/<c:.+>/<a:.+>' => '<m>/<c>/<a>',
                '<m:.+>/<c:.+>/<a:.+>/<id:\d+>' => '<m>/<c>/<a>',
            ],
        ],
        'i18n'=>[
            'translations' => [
                'view'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/i18n",
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'view'=>'view.php',
                    ]
                ],
                'model'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/i18n",
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'model'=>'model.php',
                    ]
                ],
            ]
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => [
            '192.168.*.*',
        ],
    ];
}

return $config;
