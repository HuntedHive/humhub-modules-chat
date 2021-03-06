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
