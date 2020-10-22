<?php

namespace api\modules\v1\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $user_id
 * @property int $user_type_id
 * @property string|null $auth_token
 * @property int $status_id
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Checkin[] $checkins
 * @property Checkin[] $checkins0
 * @property Customer[] $customers
 * @property Shop[] $shops
 * @property UserStatus $status
 * @property UserType $userType
 * @property UserVerification[] $userVerifications
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'user_id', 'user_type_id', 'status_id', 'created_at'], 'required'],
            [['user_type_id','status_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password', 'user_id', 'auth_token'], 'string', 'max' => 100],
            [['username'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::class(), 'targetAttribute' => ['user_type_id' => 'id']],
        ];
    }

    public function extraFields() {
        return ['userProfile'];
    }

    // filter out some fields, best used when you want to inherit the parent implementation
    // and blacklist some sensitive fields.
    public function fields() {
        $user_profile = $this->userProfile;
        $user_type = $this['userType']['user_type'];
        $user_type_code = $this['userType']['user_type_code'];

        if ($user_profile) {
            $model_class = $user_profile::className();
            $primary_field_key = $model_class::primaryKey();
            $primary_field_key = $primary_field_key[0];

            $name = $user_profile->name;
//            $profile_pic = $user_profile->getProfile_pic_url();
        } else {
            switch ($user_type_code) {
                case 'customer':
                    $primary_field_key = 'user_uid';
//                    $profile_pic = (new Customer())->dummy_avatar;
                    break;
                case 'businessowner':
                    $primary_field_key = 'user_uid';
//                    $profile_pic = (new Company())->dummy_avatar;
                    break;
                default:
                    $primary_field_key = '';
                    break;
            }

            $name = '';
        }
        $user_profile_id = $user_profile[$primary_field_key];


        $fields = [
            $primary_field_key => function($model) use ($user_profile_id) {
                return $user_profile_id;
            },
            'user_id' => 'user_id',
            'username' => 'username',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'user_type' => function($model) use ($user_type) {
                return $user_type;
            },
//            'name' => function($model) use ($name) {
//                return $name;
//            },
//            'profile_pic' => function($model) use ($profile_pic) {
//                return $profile_pic;
//            },
            'user_profile' => function($model) use ($user_profile) {
                return $user_profile;
            }
        ];
        if(Yii::$app->request->pathInfo=='v1/main/login')
            $fields['access_token'] = 'auth_token';

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'user_id' => 'User ID',
            'user_type_id' => 'User Type ID',
            'auth_token' => 'Auth Token',
            'status_id' => 'Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * Gets query for [[Checkins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCheckins()
    {
        return $this->hasMany(Checkin::className(), ['customer_uid' => 'id']);
    }

    /**
     * Gets query for [[Checkins0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCheckins0()
    {
        return $this->hasMany(Checkin::className(), ['shop_uid' => 'id']);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['user_uid' => 'id']);
    }

    /**
     * Gets query for [[Shops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['user_uid' => 'id']);
    }

    /**
     * Gets query for [[UserType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }

    public function getUser_type() {
        return $this['userType']['user_type'];
    }

    public function getUserProfile($user_type = '') {
        if (!$user_type)
            $user_type = $this->userType;

        $user_type_code = $user_type['user_type_code'];
        if ($user_type_code == 'customer')
            return $this->getCustomer();
        else if ($user_type_code == 'businessowner')
            return $this->getShop();
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(UserStatus::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[UserVerifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserVerifications()
    {
        return $this->hasMany(UserVerification::className(), ['user_uid' => 'id']);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
