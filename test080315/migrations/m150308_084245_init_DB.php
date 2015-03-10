<?php

use yii\db\Schema;
use yii\db\Migration;

class m150308_084245_init_DB extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%books}}',[
            'id' => 'pk',
            'name' => Schema::TYPE_STRING. '(100) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER. ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        $this->createTable('{{%authors}}',[
            'id' => 'pk',
            'name' => Schema::TYPE_STRING. '(100) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER. ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        $this->createTable('{{%readers}}',[
            'id' => 'pk',
            'name' => Schema::TYPE_STRING. '(100) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER. ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        // Relations

        $this->createTable('{{%authors_books}}',[
            'id' => 'pk',
            'author_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'book_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER. ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        $this->createTable('{{%readers_books}}',[
            'id' => 'pk',
            'reader_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'book_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER. ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        // Indexes

        $this->createIndex('author_id','{{%authors_books}}','author_id');
        $this->createIndex('book_id','{{%authors_books}}','book_id');
        $this->createIndex('reader_id','{{%readers_books}}','reader_id');
        $this->createIndex('book_id','{{%readers_books}}','book_id');

        // Foreign

        $this->addForeignKey('authors_books_ibfk_1','{{%authors_books}}','author_id','{{%authors}}','id','CASCADE','CASCADE');
        $this->addForeignKey('authors_books_ibfk_2','{{%authors_books}}','book_id','{{%books}}','id','CASCADE','CASCADE');
        $this->addForeignKey('readers_books_ibfk_1','{{%readers_books}}','reader_id','{{%readers}}','id','CASCADE','CASCADE');
        $this->addForeignKey('readers_books_ibfk_2','{{%readers_books}}','book_id','{{%books}}','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%authors_books}}');
        $this->dropTable('{{%readers_books}}');
        $this->dropTable('{{%readers}}');
        $this->dropTable('{{%authors}}');
        $this->dropTable('{{%books}}');

        echo "m150308_084245_init_DB has been reverted.\n";
        return true;
    }
}
