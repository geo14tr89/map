<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ken_to_user}}`.
 */
class m220108_110621_add_column_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'token', $this->char(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
