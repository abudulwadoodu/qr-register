<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "paymenttype".
 *
 * @property int $id
 * @property string $paymenttype
 */
class Paymenttype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paymenttype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paymenttype'], 'required'],
            [['paymenttype'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paymenttype' => 'Paymenttype',
        ];
    }
}
