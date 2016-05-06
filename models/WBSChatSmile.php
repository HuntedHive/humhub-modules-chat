<?php

class WBSChatSmile extends HActiveRecord
{
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
        return 'wbs_smiles';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('symbol', 'unique'),
            array('link, symbol', 'required'),
            array('link', 'length'),
            array('symbol', 'length', 'max' => 50),
            array(array('created_at', 'created_by', 'updated_at', 'updated_by', 'link'), 'safe'),
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
//            'symbol' => 'Symbol',
        );
    }

    public static function toSmile($data)
    {
        $smiles = self::model()->findAll();
        foreach ($smiles as $smile) {
            $data = preg_replace('/'. quotemeta($smile->symbol) .'/', "<img style='width:22px' src='$smile->link'>", $data);
        }

        return $data;
    }
}
