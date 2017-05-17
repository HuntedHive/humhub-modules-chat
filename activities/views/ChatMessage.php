<?php

use yii\helpers\Html;
use humhub\modules\chat\models\WBSChatSmile;

echo Yii::t('app', '<i class="fa fa-commenting-o color-chat" style="margin-right: 5px;color: #1895a4;vertical-align: middle;"></i> {userName} posted in <strong>live chat</strong> {answer}.', array(
    '{userName}' => '<strong>' . $originator->displayName . '</strong>',
    '{answer}' => WBSChatSmile::toSmile($source->getContentDescription()),
));
?>
