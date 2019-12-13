<?php

namespace general\models;

/**
 * This is the ActiveQuery class for [[ZhihuFav]].
 *
 * @see ZhihuFav
 */
class ZhihuFavQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ZhihuFav[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ZhihuFav|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
