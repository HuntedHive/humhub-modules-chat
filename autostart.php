<?php
Yii::app()->moduleManager->register(array(
    'id' => 'chat',
    'class' => 'application.modules.chat.ChatModule',
    'import' => array(
        'application.modules.chat.*',
        'application.modules.chat.models.*',
        'application.modules.chat.widgets.*',
    ),
    'events' => array(
        array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('ChatEvents', 'onTopMenuInit')),
        array('class' => 'AdminMenuWidget', 'event' => 'onInit', 'callback' => array('ChatEvents', 'onAdminMenuInit')),
        array('class' => 'DashboardSidebarWidget', 'event' => 'onInit', 'callback' => array('ChatEvents', 'onActivityInit')),
    ),
));
?>
