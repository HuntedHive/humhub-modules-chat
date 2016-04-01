<?php

/**
 * @package humhub.modules_core.admin.controllers
 * @since 0.5
 */
class ChatAdminController extends Controller
{
    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('WBSChatSmile');
        $model        = new WBSChatSmile; // error
        $this->render("index", [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new WBSChatSmile;
        $dataProvider = new CActiveDataProvider('WBSChatSmile');
        $this->performAjaxValidation($model);
        if (isset($_POST['WBSChatSmile'])) {
            
            $model->attributes = $_POST['WBSChatSmile'];
            $model->save();
        }
        $this->render("index", [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    
    public function actionDelete($id)
    {
        WBSChatSmile::model()->deleteByPk($id);
    }
    
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}