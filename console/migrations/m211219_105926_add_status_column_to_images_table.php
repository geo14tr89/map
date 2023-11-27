<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%images}}`.
 */
class m211219_105926_add_status_column_to_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('images', 'status', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('images', 'status');
    }
}
