<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "shoptype".
 *
 * @property int $id
 * @property string $shoptype
 */
class Shoptype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shoptype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shoptype'], 'required'],
            [['shoptype'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shoptype' => 'Shoptype',
        ];
    }
}
