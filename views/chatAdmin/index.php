<?php

$form = $this->beginWidget('HActiveForm',
    array(
        'id' => 'smile-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action' => ['create'],
        'focus' => array($model, 'symbol'),
    ));

?>
    <?php echo $form->errorSummary($model); ?>

<div class="row">
    
    <?php echo $form->labelEx($model, 'symbol'); ?>
    <?php echo $form->textField($model, 'symbol'); ?>
    <?php echo $form->error($model, 'symbol'); ?>
</div>
<div class="row">
<?php echo $form->labelEx($model, 'link'); ?>
<?php echo $form->textField($model, 'link'); ?>
<?php echo $form->error($model, 'link'); ?>
</div>
<div class="row submit">
    <input type='submit' class='btn btn-primary'/>
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
