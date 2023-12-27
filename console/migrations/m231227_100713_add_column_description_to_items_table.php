<?php

use yii\db\Migration;

/**
 * Class m231227_100713_add_column_description_to_items_table
 */
class m231227_100713_add_column_description_to_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('items', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231227_100713_add_column_description_to_items_table cannot be reverted.\n";

        return false;
    }
    */
}
