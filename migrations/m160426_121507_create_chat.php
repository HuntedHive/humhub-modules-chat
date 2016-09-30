<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016  Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 *
 */

use yii\db\Migration;

class m160426_121507_create_chat extends Migration
{
	public function up()
	{
		if(!\Yii::$app->db->schema->getTableSchema("wbs_chat")) {
			$this->createTable('wbs_chat', [
				'id' => 'pk',
				'user_id' => 'varchar(100) NOT NULL',
				'text' => 'text NOT NULL',
				'file' => 'text NOT NULL',
				'created_at' => 'datetime NOT NULL',
				'created_by' => 'int(11) NOT NULL',
				'updated_at' => 'datetime NOT NULL',
				'updated_by' => 'int(11) NOT NULL',
			], ''
			);
		}

		if(!\Yii::$app->db->schema->getTableSchema("wbs_smiles")) {
			$this->createTable('wbs_smiles', [
				'id' => 'pk',
				'symbol' => 'varchar(50)',
				'link' => 'text NOT NULL',
				'created_at' => 'datetime NOT NULL',
				'created_by' => 'int(11) NOT NULL',
				'updated_at' => 'datetime NOT NULL',
				'updated_by' => 'int(11) NOT NULL',
			], ''
			);
		}

		$this->addColumn('user', 'is_chating', "INT NOT NULL DEFAULT  '1'");
	}

	public function down()
	{
		$this->dropColumn("user", "is_chating");
		$this->dropTable("wbs_chat");
		$this->dropTable("wbs_smiles");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
