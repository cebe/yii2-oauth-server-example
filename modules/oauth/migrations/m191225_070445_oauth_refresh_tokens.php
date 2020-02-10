<?php

namespace app\modules\oauth\migrations;

use yii\db\Migration;

/**
 * Class m191225_070445_oauth_refresh_tokens
 */
class m191225_070445_oauth_refresh_tokens extends Migration
{
    private const TABLE_NAME = 'oauth_refresh_tokens';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(static::TABLE_NAME, [
            'id' => $this->string(128), // will contain rtefresh token string itself
            'access_token_id' => $this->string(128)->notNull(),
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
