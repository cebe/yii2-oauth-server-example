<?php

namespace app\modules\oauth\models;

use Yii;

/**
 * This is the model class for table "oauth_authorization_codes".
 *
 * @property int $id
 * @property string $authorization_code
 * @property int|null $user_id
 * @property int $oauth_client_id
 * @property string|null $scope
 * @property int|null $is_revoked
 * @property string|null $expires_at
 * @property string|null $created_at
 */
class AuthCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_auth_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authorization_code' => 'Authorization Code',
            'user_id' => 'User ID',
            'oauth_client_id' => 'Oauth Client ID',
            'scope' => 'Scope',
            'is_revoked' => 'Is Revoked',
            'expires_at' => 'Expires At',
            'created_at' => 'Created At',
        ];
    }
}
