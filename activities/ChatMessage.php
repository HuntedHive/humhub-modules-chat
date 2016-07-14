<?php

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