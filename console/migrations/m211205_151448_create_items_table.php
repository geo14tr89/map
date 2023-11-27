<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%items}}`.
 */
class m211205_151448_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'parent_id' => $this->integer(11),
            'title' => $this->char(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
    }
}
