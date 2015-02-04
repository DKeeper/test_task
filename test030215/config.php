<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 6:11
 * Created by JetBrains PhpStorm.
 */
return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=test',
        'user' => 'test',
        'password' => 'RerzSwuWPzKvxNNT',
    ],
    'rules' => [
        'user' => [
            'pattern' => '/^[a-zA-Z0-9_]+$/',
            'message' => 'Allowed characters: a-z, A-Z, digits and "_"',
        ],
    ],
];