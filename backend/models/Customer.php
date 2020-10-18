<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $user_uid
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property float|null $latitude
 * @property float|null $longitude
 *
 * @property User $userU
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_uid', 'name'], 'required'],
            [['user_uid'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'email'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * Gets query for [[UserU]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserU()
    {
        return $this->hasOne(User::className(), ['id' => 'user_uid']);
    }
}
