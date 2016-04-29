<?php
/**
 * good
 */
class ChatEvents
{
    /**
     * Add the Q&A menu item to
     * the top menu
     * @param $event
     */
    public static function onTopMenuInit($event)
    {
        if (Yii::app()->user->isGuest) {
            return;
        }

        $event->sender->addItem(array(
            'label' => Yii::t('MailModule.base', 'Chat'),
            'url' => Yii::app()->createUrl('/chat/chat/index', array()),
            'icon' => '<i class="fa fa-commenting-o"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'chat' && Yii::app()->controller->id == 'chat'),
            'sortOrder' => 300,
        ));
    }
    
    /**
     * Add the Q&A menu item to
     * the top menu
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        if (Yii::app()->user->isGuest) {
            return;
        }
        $event->sender->addItem(array(
            'label' => Yii::t('AdminModule.widgets_AdminMenuWidget', 'Chat Module'),
            'url' => Yii::app()->createUrl('chat/chatAdmin/index'),
            'icon' => '<i class="fa fa-commenting-o"></i>',
            'sortOrder' => 700,
            'group' => 'manage',
            'newItemCount' => 0,
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'chat' && Yii::app()->controller->id == 'chatAdmin'),
            'isVisible' => Yii::app()->user->isAdmin(),
        ));
    }

    public static function onActivityInit($event)
    {
        if (Yii::app()->user->isGuest) {
            return;
        }

        $event->sender->addWidget('application.modules.chat.widgets.MyTasksWidget', array(), array('sortOrder' => 1));
    }

}