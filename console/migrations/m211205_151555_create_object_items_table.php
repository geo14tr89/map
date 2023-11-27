<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%object_items}}`.
 */
class m211205_151555_create_object_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%object_items}}', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer(11),
            'item_id' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%object_categories}}');
    }
}
