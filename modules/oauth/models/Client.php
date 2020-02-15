<?php

namespace app\modules\oauth\models;

use Yii;

/**
 * This is the model class for table "oauth_clients".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $secret
 * @property string|null $redirect_uri
 * @property int|null $personal_access_client
 * @property int|null $password_client
 * @property int|null $is_revoked
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'redirect_uri'], 'required'],
            [['name', 'redirect_uri'], 'string'],
            [['name'], 'string', 'max' => 255],
            // [['redirect_uri'], 'url', 'defaultScheme' => 'http'], // for local dev don't put this validation
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'secret' => 'Secret',
            'redirect_uri' => 'Redirect Uri',
            'personal_access_client' => 'Personal Access Client',
            'password_client' => 'Password Client',
            'is_revoked' => 'Is Revoked',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\Da\User\Model\User::class, ['id' => 'user_id']);
    }
}
