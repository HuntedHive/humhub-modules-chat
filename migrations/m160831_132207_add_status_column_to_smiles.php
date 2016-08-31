<?php

use humhub\components\Migration;

/**
 * Handles adding status_column to table `smiles`.
 */
class m160831_132207_add_status_column_to_smiles extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn("wbs_smiles", 'status', "INT NOT NULL DEFAULT  '1'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn("wbs_smiles", 'status');
    }
}
