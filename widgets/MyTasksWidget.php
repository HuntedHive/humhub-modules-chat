<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 */

namespace humhub\modules\chat\widgets;

use humhub\components\Widget;

class MyTasksWidget extends Widget {

	protected $themePath = 'modules/tasks';

	/**
	 * Creates the Wall Widget
	 */
	public function run()
	{
		return $this->render('buttonChat');
	}
}

?>
