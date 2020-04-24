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
        $this->createTable(static::TABLE_NAME, [
            'id' => $this->string(128), // id will contain auth code
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
}
