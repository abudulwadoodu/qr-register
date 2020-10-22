<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "user_status".
 *
 * @property int $id
 * @property string $user_status_code
 * @property string $user_status
 *
 * @property User[] $users
 */
class UserStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_status_code', 'user_status'], 'required'],
            [['user_status_code'], 'string'],
            [['user_status'], 'string', 'max' => 50],
        ];
    }

    public function fields()
    {
        return ['id', 'code'=>'user_status_code', 'name' =>'user_status'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_status_code' => 'User Status Code',
            'user_status' => 'User Status',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['status_id' => 'id']);
    }
}
