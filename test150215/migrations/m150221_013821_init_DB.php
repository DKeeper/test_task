<?php

use yii\db\Schema;
use yii\db\Migration;

class m150221_013821_init_DB extends Migration
{
    public function safeUp()
    {
        $this->createTable('mfr',[
            'id' => 'pk',
            'title' => Schema::TYPE_STRING. '(30) NOT NULL',
        ]);

        $this->createTable('product',[
            'id' => 'pk',
            'name' => Schema::TYPE_STRING. '(50) NOT NULL',
            'mfr_id' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);

        $this->createIndex('mfr_id','product','mfr_id');

        $this->addForeignKey('product_ibfk_1','product','mfr_id','mfr','id','CASCADE','CASCADE');

        $this->batchInsert('mfr',['id','title'],[
            [1,'Manufacturer 1'],
            [2,'Manufacturer 2'],
            [3,'Manufacturer 3'],
            [4,'Manufacturer 4'],
        ]);

        $this->batchInsert('product',['id','name','mfr_id'],[
            [1,'Product 1 (mfr 1)',1],
            [2,'Product 1 (mfr 2)',2],
            [3,'Product 1 (mfr 3)',3],
            [4,'Product 1 (mfr 4)',4],
            [5,'Product 2 (mfr 1)',1],
            [6,'Product 2 (mfr 3)',3],
            [7,'Product 3 (mfr 2)',2],
            [8,'Product 3 (mfr 4)',4],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('product');
        $this->dropTable('mfr');

        echo "m150221_013821_init_DB has been reverted.\n";
        return true;
    }
}
