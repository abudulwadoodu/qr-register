<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_verification".
 *
 * @property int $id
 * @property int $user_uid
 * @property string $verification_token
 * @property string $token_salt
 * @property string $verification_type
 *
 * @property User $userU
 */
class UserVerification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_verification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_uid', 'verification_token', 'token_salt', 'verification_type'], 'required'],
            [['user_uid'], 'integer'],
            [['verification_type'], 'string'],
            [['verification_token'], 'string', 'max' => 100],
            [['token_salt'], 'string', 'max' => 20],
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
            'verification_token' => 'Verification Token',
            'token_salt' => 'Token Salt',
            'verification_type' => 'Verification Type',
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
