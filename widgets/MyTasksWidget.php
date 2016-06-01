<?php

class MyTasksWidget extends HWidget {

	protected $themePath = 'modules/tasks';

	/**
	 * Creates the Wall Widget
	 */
	public function run() {
		if(Yii::app()->controller->id != "chat") {
			$this->render('buttonChat');
		}
	}
}

?>