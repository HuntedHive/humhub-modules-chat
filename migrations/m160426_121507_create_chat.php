<?php

class m160426_121507_create_chat extends EDbMigration
{
	public function up()
	{
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