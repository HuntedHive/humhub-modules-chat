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

<div class="row">
    <div class="col-sm-5">
        <?php echo $form->textField($model, 'symbol', array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter symbol *',)); ?>
        <?php echo $form->error($model, 'symbol'); ?>
    </div>
    <div class="col-sm-5">
        <?php echo $form->textField($model, 'link', array('class' => 'form-control input-sm pull-left', 'placeholder' => 'Enter link *',)); ?>
        <?php echo $form->error($model, 'link'); ?>
    </div>
    <div class="col-sm-2 submit">
        <input type='submit' class='btn btn-primary btn-sm'/>
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
            'name' => 'link',
            'type' => 'image',
            'value' => '$data->link',
        ),
        'created_at',
        'updated_at',
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
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