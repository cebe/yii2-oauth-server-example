<?php

namespace app\modules\oauth\migrations;

use yii\db\Migration;

/**
 * Class m191224_183100_oauth_access_tokens
 */
class m191224_183100_oauth_access_tokens extends Migration
{
    public const TABLE_NAME = 'oauth_access_tokens';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(static::TABLE_NAME, [
            'id' => $this->string(128), // will contain access_token string itself
            'user_id' => $this->integer()->notNull(),
            'oauth_client_id' => $this->integer()->notNull(),
            'scopes' => $this->text(),
            'is_revoked' => $this->boolean()->defaultValue(true),
            'expires_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addPrimaryKey(static::TABLE_NAME.'_id_pk', static::TABLE_NAME, 'id');
    }

    // TODO add 2 foreign key

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}
