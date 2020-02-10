<?php

namespace app\modules\oauth\models;

use Yii;

/**
 * This is the model class for table "oauth_scopes".
 *
 * @property int $id
 * @property string $scope
 * @property string|null $description
 * @property int|null $is_default
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class Scope extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_scopes';
    }

    /**
     * {@inheritdoc}
     */
    // public function rules()
    // {
    //     return [
    //         [['scope'], 'required'],
    //         [['description'], 'string'],
    //         [['is_default', 'created_by', 'updated_by'], 'integer'],
    //         [['created_at', 'updated_at'], 'safe'],
    //         [['scope'], 'string', 'max' => 100],
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scope' => 'Scope',
            'description' => 'Description',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
