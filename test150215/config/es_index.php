<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 21.02.15
 * @time 9:58
 * Created by JetBrains PhpStorm.
 *
 * Конфигурация индекса для поддержки русского языка, добавлен фильтр, удаляющий html теги из индексируемого текста
 */
return [
    'settings' => [
        "analysis" => [
            "filter" => [
                "ru_stop" => [
                    "type"=> "stop",
                    "stopwords"=> "_russian_"
                ],
                "ru_stemmer" => [
                    "type"=> "stemmer",
                    "language"=> "russian"
                ]
            ],
            "analyzer" => [
                "default" => [
                    "char_filter" => [
                        "html_strip"
                    ],
                    "tokenizer" => "standard",
                    "filter" => [
                        "lowercase",
                        "ru_stop",
                        "ru_stemmer"
                    ]
                ]
            ]
        ]
    ]
];