<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "batte_array".
 *
 * @property integer $id
 * @property string $title
 * @property string $playway
 * @property string $heros
 * @property string $hero_equipments
 * @property integer $update_time
 * @property integer $create_time
 */
class BatteArray extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batte_array';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'heros', 'hero_equipments'], 'required'],
            [['playway', 'heros', 'hero_equipments'], 'string'],
            [['update_time', 'create_time'], 'integer'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'playway' => 'Playway',
            'heros' => 'Heros',
            'hero_equipments' => 'Hero Equipments',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
