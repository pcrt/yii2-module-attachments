<?php

namespace pcrt\file\migrations;

use yii\db\Migration;

/**
 * Class m211005_093623_create_attachment
 */
class m211005_093623_create_attachment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            /* http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci */
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('Attachments', [
            'id' => $this->primaryKey(),
            'mimetype' => $this->string(),
            'size' => $this->integer(),
            'title' => $this->string(),
            'original_filename' => $this->string(),
            'url' => $this->string(),
            'tmp_url' => $this->string(),
            'external_id' => $this->integer(),
            'external_table' => $this->string(),
            'extra' => $this->string(),
            'expired_date' => $this->date(),
            'archived' => $this->integer()->null(),
            'version' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-archived',
            'Attachments',
            'archived'
        );

        $this->addForeignKey(
            'fk-Attachments-archived',
            'Attachments',
            'archived',
            'Attachments',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('Attachments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211005_093623_create_attachment cannot be reverted.\n";

        return false;
    }
    */
}
