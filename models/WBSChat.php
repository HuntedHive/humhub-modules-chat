<?php

namespace humhub\modules\chat\models;
use humhub\components\ActiveRecord;
use humhub\modules\content\interfaces\ContentTitlePreview;
use humhub\modules\user\models\User;

class WBSChat extends ActiveRecord implements ContentTitlePreview
{
    
    const ABLE_WRITE = 1;
    const DISABLE_WRITE = 0;
    
    public static $write = [
        self::ABLE_WRITE => 'not banned',
        self::DISABLE_WRITE => 'is banned',
    ];

    public function getContentName()
    {
        return Yii::t('CommentModule.models_comment', 'Post');
    }

    public function getContentDescription()
    {
        return $this->text;
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'wbs_chat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['text', 'text'],
            ['user_id', 'integer'],
            array(array('file','created_at', 'created_by', 'updated_at', 'updated_by'), 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            //'module_id' => 'Module',
        );
    }

    public static function isChating($user_id)
    {
        $user = User::findOne($user_id);
        if(!empty($user)) {
            return (bool)$user->is_chating;
        }
    }
}
