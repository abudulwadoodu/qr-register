<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property int $user_uid
 * @property string $name
 * @property string|null $registration_id
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $phone_2
 * @property string|null $email
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $contact_person
 * @property string|null $contact_number
 * @property int|null $shop_type_id
 * @property int|null $payment_type_id
 * @property string|null $photo
 *
 * @property User $userU
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_uid', 'name'], 'required'],
            [['user_uid', 'shop_type_id', 'payment_type_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'email', 'photo'], 'string', 'max' => 100],
            [['registration_id', 'phone', 'phone_2', 'contact_person', 'contact_number'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
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
            'registration_id' => 'Registration ID',
            'address' => 'Address',
            'phone' => 'Phone',
            'phone_2' => 'Phone 2',
            'email' => 'Email',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'contact_person' => 'Contact Person',
            'contact_number' => 'Contact Number',
            'shop_type_id' => 'Shop Type ID',
            'payment_type_id' => 'Payment Type ID',
            'photo' => 'Photo',
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
