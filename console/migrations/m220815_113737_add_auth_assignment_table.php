<?php

use yii\db\Migration;

/**
 * Class m220815_113737_add_auth_assignment_table
 */
class m220815_113737_add_auth_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth_assignment', [
            'item_name' => $this->text()->notNull(),
            'user_id' => $this->char(64)->notNull(),
            'created_at' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth_assignment');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220815_113737_add_auth_assignment_table cannot be reverted.\n";

        return false;
    }
    */
}
