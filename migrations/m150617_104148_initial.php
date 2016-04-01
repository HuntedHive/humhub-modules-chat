<?php

class m150617_104148_initial extends EDbMigration
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
    }

    public function down()
    {
        $this->dropTable("wbs_chat");
        $this->dropTable("wbs_smiles");
    }
}
