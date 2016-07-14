<?php

namespace humhub\modules\chat\widgets;

use humhub\components\Widget;

class MyTasksWidget extends Widget {

	protected $themePath = 'modules/tasks';

	/**
	 * Creates the Wall Widget
	 */
	public function run()
	{
		if(\Yii::$app->controller->id != "chat") {
			return $this->render('buttonChat');
		}
	}
}

?>