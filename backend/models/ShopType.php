<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "shop_type".
 *
 * @property int $id
 * @property string $shop_type
 */
class ShopType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_type'], 'required'],
            [['shop_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_type' => 'Shop Type',
        ];
    }
}
