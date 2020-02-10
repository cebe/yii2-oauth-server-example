<?php

namespace app\modules\oauth\models;

use Yii;

/**
 * This is the model class for table "oauth_refresh_tokens".
 *
 * @property int $id
 * @property string $refresh_token
 * @property int|null $is_revoked
 * @property string|null $expires_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class RefreshToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_refresh_tokens';
    }

    /**
     * {@inheritdoc}
     */
    // public function rules()
    // {
    //     return [
    //         [['refresh_token'], 'required'],
    //         [['refresh_token'], 'string'],
    //         [['is_revoked'], 'integer'],
    //         [['expires_at', 'created_at', 'updated_at'], 'safe'],
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'refresh_token' => 'Refresh Token',
            'is_revoked' => 'Is Revoked',
            'expires_at' => 'Expires At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
