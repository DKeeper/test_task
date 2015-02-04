<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:11
 * Created by JetBrains PhpStorm.
 */
return [
    'language'=>'en',
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=test',
        'user' => 'test',
        'password' => 'RerzSwuWPzKvxNNT',
    ],
    'rules' => [
        'login' => [
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'password' => [
            ['type'=>'length','message'=>'Min length - ','min',6],
        ],
        'email' => [
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
                'message' => 'Invalid email',
            ]
        ],
        'phone' => [
            [
                'type' => 'regExp',
                'pattern' => '/^\+\d{10,10}$/',
                'message' => 'Invalid format. Only +1234567890',
            ]
        ],
        'first_name' => [
            ['type'=>'length','message'=>'Max length - ','max',15],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'last_name' => [
            ['type'=>'length','message'=>'Max length - ','max',15],
            [
                'type' => 'regExp',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
            ],
        ],
        'avatar' => [
            [
                'type' => 'file',
                'allowedType' => ['jpeg','gif','png'],
                'message' => 'Allowed extension: jpg,gif,png',
            ]
        ],
    ],
];