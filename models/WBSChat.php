<?php

class WBSChat extends HActiveRecordContent
{
    
    const ABLE_WRITE = 1;
    const DISABLE_WRITE = 0;
    
    public static $write = [
        self::ABLE_WRITE => 'not banned',
        self::DISABLE_WRITE => 'has banned',
    ];
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ModuleEnabled the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
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
}
