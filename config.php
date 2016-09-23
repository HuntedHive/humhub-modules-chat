<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 */

use humhub\widgets\TopMenu;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\dashboard\widgets\Sidebar;

return [
    'id' => 'chat',
    'class' => 'humhub\modules\chat\Module',
    'namespace' => 'humhub\modules\chat',
    'events' => array(
        array('class' => TopMenu::className(), 'event' => TopMenu::EVENT_INIT, 'callback' => array('humhub\modules\chat\Events', 'onTopMenuInit')),
        array('class' => AdminMenu::className(), 'event' => AdminMenu::EVENT_INIT, 'callback' => array('humhub\modules\chat\Events', 'onAdminMenuInit')),
        array('class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => array('humhub\modules\chat\Events', 'onActivityInit')),
    ),
];
?>
