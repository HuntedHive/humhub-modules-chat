<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 */

namespace humhub\modules\chat\models;
use humhub\components\ActiveRecord;


class WBSChatSmile extends ActiveRecord
{

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 2;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
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
            array(['link', 'symbol'], 'required'),
            array('link', 'string'),
            array('symbol', 'string', 'max' => 50),
            array('status' , 'integer'),
            array(array('created_at', 'created_by', 'updated_at', 'updated_by', 'link'), 'safe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
//            'symbol' => 'Symbol',
            'link' => 'Enter image name with extension',
        );
    }

    public static function toSmile($data)
    {
        $smiles = self::find()->all();
        foreach ($smiles as $smile) {
            $data = preg_replace('/'. quotemeta($smile->symbol) .'/', "<img style='width:22px' src='". \Yii::$app->request->baseUrl . "/uploads/emojione/$smile->link'>", $data);
        }

        return $data;
    }
}
