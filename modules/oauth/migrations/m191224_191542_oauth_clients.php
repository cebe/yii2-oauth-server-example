<?php

namespace app\modules\oauth\migrations;

use yii\db\Migration;

/**
 * Class m191224_191542_oauth_clients
 */
class m191224_191542_oauth_clients extends Migration
{
    public const TABLE_NAME = 'oauth_clients';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(static::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'secret' => $this->string(128),
            'redirect_uri' => $this->text()->notNull(),

            'personal_access_client' => $this->boolean()->defaultValue(false),
            'password_client' => $this->boolean()->defaultValue(false),

            'is_revoked' => $this->boolean()->defaultValue(true),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // TODO user_id add index
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}
