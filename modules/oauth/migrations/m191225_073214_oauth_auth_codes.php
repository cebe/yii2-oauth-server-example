<?php

namespace app\modules\oauth\migrations;

use yii\db\Migration;

/**
 * Class m191225_073214_oauth_authorization_codes
 */
class m191225_073214_oauth_auth_codes extends Migration
{
    private const TABLE_NAME = 'oauth_auth_codes';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // CREATE TABLE oauth_authorization_codes (
        //   authorization_code  VARCHAR(40)     NOT NULL,
        //   client_id           VARCHAR(80)     NOT NULL,
        //   user_id             VARCHAR(80),
        //   redirect_uri        VARCHAR(2000),
        //   expires             TIMESTAMP       NOT NULL,
        //   scope               VARCHAR(4000),
        //   id_token            VARCHAR(1000),
        //   PRIMARY KEY (authorization_code)
        // );
        //
        // Schema::create('oauth_auth_codes', function (Blueprint $table) {
        //     $table->string('id', 100)->primary();
        //     $table->bigInteger('user_id');
        //     $table->unsignedInteger('client_id');
        //     $table->text('scopes')->nullable();
        //     $table->boolean('revoked');
        //     $table->dateTime('expires_at')->nullable();
        // });

        $this->createTable(static::TABLE_NAME, [
            'id' => $this->string(128),
            // 'authorization_code' => $this->string(100)->notNull(),
            'user_id' => $this->integer(),
            'oauth_client_id' => $this->integer()->notNull(),
            'scopes' => $this->text(),
            'is_revoked' => $this->boolean()->defaultValue(true),
            'expires_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addPrimaryKey(static::TABLE_NAME.'_id_pk', static::TABLE_NAME, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191225_073214_oauth_authorization_codes cannot be reverted.\n";

        return false;
    }
    */
}
