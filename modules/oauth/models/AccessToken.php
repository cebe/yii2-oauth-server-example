<?php

namespace app\modules\oauth\models;

use Yii;

/**
 * This is the model class for table "oauth_access_tokens".
 *
 * @property int $id
 * @property string $access_token
 * @property string|null $name
 * @property int|null $user_id
 * @property int $oauth_client_id
 * @property string|null $scopes
 * @property string|null $expires_at
 * @property int|null $is_revoked
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_access_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'name' => 'Name',
            'user_id' => 'User ID',
            'oauth_client_id' => 'Oauth Client ID',
            'scopes' => 'Scopes',
            'expires_at' => 'Expires At',
            'is_revoked' => 'Is Revoked',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getClientowner()
    {
        return $this->hasOne(Client::class, ['id' => 'oauth_client_id']);
    }
}
