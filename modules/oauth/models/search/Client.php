<?php

namespace app\modules\oauth\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\oauth\models\Client as ClientModel;

/**
 * Client represents the model behind the search form of `\app\modules\oauth\models\Client`.
 */
class Client extends ClientModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'personal_access_client', 'password_client', 'is_revoked'], 'integer'],
            [['name', 'secret', 'redirect_uri', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ClientModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'personal_access_client' => $this->personal_access_client,
            'password_client' => $this->password_client,
            'is_revoked' => $this->is_revoked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'secret', $this->secret])
            ->andFilterWhere(['like', 'redirect_uri', $this->redirect_uri]);

        return $dataProvider;
    }
}
