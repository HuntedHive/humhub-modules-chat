<?php

use yii\bootstrap\ActiveForm;
use humhub\modules\chat\models\WBSChat;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<?php


$form = ActiveForm::begin([
        'id' => 'smile-form',
    ]);

?>

<h3>Emoticons</h3>
<p class="help-block"><strong>Note:</strong> This functionality is responsible for adding new emoticons which display on Live Chat</p>
<p class="help-block"><strong>Example:</strong> Symbol: :) AND Image Name: 263a.png</p>
<p class="help-block">You can find a listing of file names associated with available icons <a href="http://emojione.com/releases/" target="_blank">here</a>. The filename is the character string which is a set of letters and numbers under the emoji description on the left hand side of the page. Make sure to append the file name with the .png extension when adding the url below.</p>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
            <?php echo $form->field($model, 'symbol')->textInput(array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter symbol *')); ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <?php echo $form->field($model, 'link')->textInput(array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter image name with extension *',)); ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-sm-2 submit">
        <input type='submit' class='btn btn-primary btn-sm' value="Save"/>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
echo \yii\grid\GridView::widget(
    array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'symbol',
        array(
            'attribute' => 'Smile Image',
            'format' => 'raw',
            'value' => function($data) {
                return Html::img($data->link, [
                    'alt'=>'emojine',
                    'style' => 'width:30px;'
                ]);
            }
        ),
        'created_at',
        'updated_at',
        array(
            'class' => \yii\grid\ActionColumn::className(),
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url,$model) {
                    return Html::a("delete", Url::toRoute(["/chat/chat-admin/delete", "id"=>$model->id]), array('class' => 'btn btn-danger btn-xs tt', 'title' => 'delete'));
                }
            ]
        ),
    ),
));
?>

<br><br><hr><br>

<h3>Banned Users</h3>

<?php
echo \yii\grid\GridView::widget(
    array(
    'dataProvider' => $dataProviderUser,
    'columns' => array(
        'username',
        array(
                'format'=>'html',
                'value' => function ($data) {
                    echo \yii\bootstrap\Html::a(WBSChat::$write[$data->is_chating], "#", array("class" => "editable-flag", "data-pk" => $data->id));
                },
                'label' => 'Banned Status',
        ),
    ),
));
?>

<br><br><hr><br>

<script>
    $(document).ready(function(){
        var select = '<?= json_encode(WBSChat::$write) ?>';

            $('.editable-flag').editable({
                mode: 'inline',
                type: 'select',
                send: 'always',
                source: JSON.parse(select),
                url: "<?php echo \Yii::$app->urlManager->createUrl('chat/chat-admin/ban') ?>", //ban action
                dataType: 'post'
            });
    });
</script>