<?php

use humhub\components\Migration;
use humhub\modules\chat\models\WBSChatSmile;
use humhub\models\Setting;

class m160822_082640_chat_add_all_emotions extends Migration
{
    public function up()
    {
        $setting = (bool)Setting::Get("chatSimle");
        if(!$setting) {
            $i = 0;

            $listFiles = [];
            $modulePath = Yii::getAlias('@webroot/protected/modules/chat/assets/icons/emojione');
            $listFiles = scandir($modulePath);
            unset($listFiles[0]);unset($listFiles[1]);
            foreach ($listFiles as $file) {
                preg_match("/([a-zA-Z0-9]+.(png|jpeg|jpg))/i", $file, $matches);
                if(isset($matches[0])) {
                    $model = new WBSChatSmile();
                    $model->link = $file;
                    $model->symbol = ":" . ++$i . ":";
                    $model->save();
                }
            }
            Setting::Set("chatSimle", 1);
        }
    }

    public function down()
    {
        echo "m160822_082640_chat_add_all_emotions cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
