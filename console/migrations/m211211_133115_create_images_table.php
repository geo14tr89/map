<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images}}`.
 */
class m211211_133115_create_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%images}}', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer(11),
            'preview_url' => $this->text(),
            'full_url' => $this->text(),
            'title' => $this->char(255),
            'description' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%images}}');
    }
}
