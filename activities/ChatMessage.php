<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 */

namespace humhub\modules\chat\activities;

use humhub\modules\activity\components\BaseActivity;

class ChatMessage extends BaseActivity
{
    /**
     * @inheritdoc
     */
    public $moduleId = 'chat';

    /**
     * @inheritdoc
     */
    public $viewName = 'ChatMessage';

}
