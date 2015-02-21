##Тестовое задание №1
https://docs.google.com/document/d/1aArpaIDQQbMzcADY2kxB338Cqy4CcfNEnE-5Vl3fjCU/edit#

Файлы:
* test1/*

##Тестовое задание №2
https://docs.google.com/document/d/1K25Snfgf9wBJ1VVjfWXNhU-5TQAE0OVwmGshdYkUBcQ/edit#

Файлы:
* test2/*

##Тестовое задание №3
https://drive.google.com/file/d/0B4ZO20ae-So9TWxSWmtIalpTNXM/view?usp=sharing

Файлы:
* test030215/*

##Тестовое задание №4
https://drive.google.com/file/d/0B4ZO20ae-So9SVk5LXQxdWpEUlk/view?usp=sharing

Файлы:

* test150215/*

###Настройка

Выполнить в корневой папке проекта

```
php composer update
```

В файлах конфигурации *config/web.php* и *common/console.php* необходимо указать адрес для соединения с elasticsearch

```php
return [
    ...
    'components' => [
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
            ],
        ],
    ],
    ...
];
```

а так же в файле *config/db.php* указать данные для соединения с БД

```php
return [
    ...
    'dsn' => 'mysql:host=localhost;dbname=dbname',
    'username' => 'username',
    'password' => 'passwrod',
    ...
];
```

В файле конфигурации *config/es_index.php* можно изменить настройки индекса

Далее необходимо выполнить первичную настройку БД и индекса. Для этого запускаем миграцию из корневой папки:

```
php yii migrate
```

Если корневая папка настроена на папку */web*, то верны следующие пути:
* mfr - редактирование поставщиков
* product - редактирование товаров
* site/equipment - связанные выпадающие списки
* site/offers - поиск