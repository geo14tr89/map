<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%objects}}`.
 */
class m211205_151119_create_objects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%objects}}', [
            'id' => $this->primaryKey(),
            'latitude' => $this->float(),
            'longitude' => $this->float(),
            'title' => $this->char(255),
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%objects}}');
    }
}
