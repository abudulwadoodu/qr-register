<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "checkin".
 *
 * @property int $id
 * @property int $customer_uid
 * @property int $shop_uid
 * @property string $check_in_time
 * @property string|null $check_out_time
 *
 * @property User $customerU
 * @property User $shopU
 */
class Checkin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checkin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_uid', 'shop_uid', 'check_in_time'], 'required'],
            [['customer_uid', 'shop_uid'], 'integer'],
            [['check_in_time', 'check_out_time'], 'safe'],
            [['customer_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_uid' => 'id']],
            [['shop_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['shop_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_uid' => 'Customer Uid',
            'shop_uid' => 'Shop Uid',
            'check_in_time' => 'Check In Time',
            'check_out_time' => 'Check Out Time',
        ];
    }

    /**
     * Gets query for [[CustomerU]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerU()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_uid']);
    }

    /**
     * Gets query for [[ShopU]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopU()
    {
        return $this->hasOne(User::className(), ['id' => 'shop_uid']);
    }
}
