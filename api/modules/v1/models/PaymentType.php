<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "payment_type".
 *
 * @property int $id
 * @property string $payment_type
 */
class PaymentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_type'], 'required'],
            [['payment_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_type' => 'Payment Type',
        ];
    }
}
