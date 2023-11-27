<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%objects}}`.
 */
class m211219_110435_add_status_column_to_objects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('objects', 'status', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('objects', 'status');
    }
}
