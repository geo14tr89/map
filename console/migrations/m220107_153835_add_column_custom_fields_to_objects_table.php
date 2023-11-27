<?php

use yii\db\Migration;

/**
 * Class m220107_153835_add_column_custom_fields_to_objects_table
 */
class m220107_153835_add_column_custom_fields_to_objects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('objects', 'custom_fields', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220107_153835_add_column_custom_fields_to_objects_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220107_153835_add_column_custom_fields_to_objects_table cannot be reverted.\n";

        return false;
    }
    */
}
