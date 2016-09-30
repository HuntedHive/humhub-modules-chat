<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016  Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 *
 */

namespace humhub\modules\chat;

use Yii;

/**
 * good
 */
class Events extends \yii\base\Object
{
    /**
     * Add the Q&A menu item to
     * the top menu
     * @param $event
     */
    public static function onTopMenuInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addItem(array(
            'label' => Yii::t('MailModule.base', 'Live Chat'),
            'url' => Yii::$app->urlManager->createUrl('/chat/chat/index', array()),
            'icon' => '<i class="fa fa-commenting-o"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'chat' && Yii::$app->controller->id == 'chat'),
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
        if (Yii::$app->user->isGuest) {
            return;
        }
        $event->sender->addItem(array(
            'label' => Yii::t('AdminModule.widgets_AdminMenuWidget', 'Chat Module'),
            'url' => Yii::$app->urlManager->createUrl('/chat/chat-admin/index'),
            'icon' => '<i class="fa fa-commenting-o"></i>',
            'sortOrder' => 700,
            'group' => 'manage',
            'newItemCount' => 0,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'chat' && Yii::$app->controller->id == 'chatAdmin'),
            'isVisible' => Yii::$app->user->isAdmin(),
        ));
    }

    public static function onActivityInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        //$event->sender->addWidget('application.modules.chat.widgets.MyTasksWidget', array(), array('sortOrder' => 1));
    }

}
