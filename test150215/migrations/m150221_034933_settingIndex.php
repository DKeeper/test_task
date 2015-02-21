<?php
/**
 * Миграция, настраивающая индекс в соответствии с конфигурацией файла config/es_index.php
 * Не настроена обработка ситуации, когда нет соединения с elasticsearch
 */

use yii\db\Migration;
use app\models\EDocument;

class m150221_034933_settingIndex extends Migration
{
    public function up()
    {
        $command = EDocument::find()->createCommand();

        $settingsWithAnalizer = require_once(Yii::getAlias('@app/config/es_index.php'));

        if($command->db->head([EDocument::index()])){
            $command->db->post([EDocument::index(),'_close']);
            $command->db->put([EDocument::index(),'_settings'],[],\yii\helpers\Json::encode($settingsWithAnalizer));
            $command->db->post([EDocument::index(),'_open']);
        } else {
            $command->db->put([EDocument::index()],[],\yii\helpers\Json::encode($settingsWithAnalizer));
        }
    }

    public function down()
    {
        $command = EDocument::find()->createCommand();

        $command->db->delete([EDocument::index()]);

        echo "m150221_034933_settingIndex has been reverted.\n";

        return true;
    }
}
