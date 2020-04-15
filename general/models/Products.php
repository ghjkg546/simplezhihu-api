<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property string $_id
 * @property integer $store_id
 * @property integer $standard_id
 * @property string $erp_goods_id
 * @property string $attribute_id
 * @property string $department_id
 * @property integer $symptom_id
 * @property integer $prepared_id
 * @property integer $tags_id
 * @property string $license
 * @property string $bar_code
 * @property string $general_name
 * @property string $first_pinyin
 * @property string $english_name
 * @property string $product_name
 * @property string $produce_unit
 * @property string $norms
 * @property string $product_code
 * @property string $unit
 * @property string $standard_image
 * @property integer $is_prescription
 * @property integer $take_days
 * @property integer $sort
 * @property integer $sales_state
 * @property integer $product_source
 * @property string $create_time
 * @property integer $last_modify_time
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'store_id', 'create_time', 'last_modify_time'], 'required'],
            [['store_id', 'standard_id', 'attribute_id', 'department_id', 'symptom_id', 'prepared_id', 'tags_id', 'is_prescription', 'take_days', 'sort', 'sales_state', 'product_source', 'create_time', 'last_modify_time'], 'integer'],
            [['first_pinyin', 'standard_image'], 'string'],
            [['_id'], 'string', 'max' => 32],
            [['erp_goods_id', 'norms'], 'string', 'max' => 255],
            [['license', 'bar_code', 'english_name', 'product_code'], 'string', 'max' => 100],
            [['general_name', 'product_name', 'produce_unit', 'unit'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'Id',
            'store_id' => 'Store ID',
            'standard_id' => 'Standard ID',
            'erp_goods_id' => 'Erp Goods ID',
            'attribute_id' => 'Attribute ID',
            'department_id' => 'Department ID',
            'symptom_id' => 'Symptom ID',
            'prepared_id' => 'Prepared ID',
            'tags_id' => 'Tags ID',
            'license' => 'License',
            'bar_code' => 'Bar Code',
            'general_name' => 'General Name',
            'first_pinyin' => 'First Pinyin',
            'english_name' => 'English Name',
            'product_name' => 'Product Name',
            'produce_unit' => 'Produce Unit',
            'norms' => 'Norms',
            'product_code' => 'Product Code',
            'unit' => 'Unit',
            'standard_image' => 'Standard Image',
            'is_prescription' => 'Is Prescription',
            'take_days' => 'Take Days',
            'sort' => 'Sort',
            'sales_state' => 'Sales State',
            'product_source' => 'Product Source',
            'create_time' => 'Create Time',
            'last_modify_time' => 'Last Modify Time',
        ];
    }
}
