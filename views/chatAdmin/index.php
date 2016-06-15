<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<?php


$form = $this->beginWidget('HActiveForm',
    array(
        'id' => 'smile-form',
//        'enableAjaxValidation' => true,
//        'enableClientValidation' => true,
//        'action' => ['create'],
        'focus' => array($model, 'symbol'),
    ));

?>

<h3>Emoticons</h3>
<p class="help-block"><strong>Note:</strong> This functionality is responsible for adding new emoticons which display on Live Chat</p>
<p class="help-block"><strong>Example:</strong> Symbol: :) AND Image Name: 263a.png</p>
<p class="help-block">You can find a listing of file names associated with available icons <a href="http://emojione.com/releases/" target="_blank">here</a>. The filename is the character string which is a set of letters and numbers under the emoji description on the left hand side of the page. Make sure to append the file name with the .png extension when adding the url below.</p>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
            <?php echo $form->textField($model, 'symbol', array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter symbol *',)); ?>
            <?php echo $form->error($model, 'symbol'); ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <?php echo $form->textField($model, 'link', array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter image name with extension *',)); ?>
            <?php echo $form->error($model, 'link'); ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-sm-2 submit">
        <input type='submit' class='btn btn-primary btn-sm' value="Save"/>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$this->widget('zii.widgets.grid.CGridView',
    array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'symbol',
        array(
            'name' => 'Smile Image',
            'type' => 'image',
            'value' => '$data->link',
        ),
        'created_at',
        'updated_at',
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => array
                (
                    'label'=>'<i class="fa fa-times"></i>',
                    'imageUrl'=>false,
                    'options'=>array('class'=>'btn btn-danger btn-xs tt', 'title' => 'delete'),
                    'url'=>'Yii::app()->createUrl("/chat/chatAdmin/delete", array("id"=>$data->id))',
                ),
            ]
        ),
    ),
));
?>

<br><br><hr><br>

<h3>Banned Users</h3>

<?php
$this->widget('zii.widgets.grid.CGridView',
    array(
    'dataProvider' => $dataProviderUser,
    'columns' => array(
        'username',
        array(
                'type'=>'html',
                'value' => function ($data) {
                    echo CHtml::link(WBSChat::$write[$data->is_chating], "#", array("class" => "editable-flag", "data-pk" => $data->id));
                },
                'name' => 'Banned Status',
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
                url: "<?php echo \Yii::app()->createUrl('chat/chatAdmin/ban') ?>", //ban action
                dataType: 'post'
            });
    });
</script>