<?php

namespace backend\models\search;

use backend\models\Image;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ImageSearch extends Image
{

    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [['id', 'status'], 'integer'],
        ]);
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
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
        $query = Image::find();

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
            'object_id' => $this->object_id,
            'title' => $this->title,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'full_url', $this->full_url])
            ->andFilterWhere(['like', 'preview_url', $this->preview_url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
