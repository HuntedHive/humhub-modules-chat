<?php

class MyTasksWidget extends HWidget {

	protected $themePath = 'modules/tasks';

	/**
	 * Creates the Wall Widget
	 */
	public function run() {
		$this->render('buttonChat');
	}

}

?>