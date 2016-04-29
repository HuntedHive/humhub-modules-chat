<?php if(!empty($user) && !empty($target) && (\Yii::app()->user->id != $target->created_by)) {  ?>
    <?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
    <?php
        echo Yii::t('TasksModule.views_activities_TaskCreated', '<i class="fa fa-commenting-o color-chat" style="margin-right: 5px;color: #1895a4;vertical-align: middle;"></i> {userName} posted in <strong>live chat</strong> "{message}".', array(
            '{userName}' => '<strong>' . CHtml::encode($user->displayName) . '</strong>',
            '{message}' => '<strong>' . WBSChatSmile::toSmile(ActivityModule::formatOutput($target->text)) . '</strong>'
        ));
    ?>
    <?php $this->endContent(); ?>
<?php } ?>

