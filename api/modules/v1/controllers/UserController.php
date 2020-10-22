<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\User;

class UserController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\User';
    public $serializer;

    public function init() {
        $this->serializer = Yii::$app->params['serializer'];
        $this->serializer['collectionEnvelope'] = 'users';
    }

    public function actions() {
        $actions = parent::actions();
        if (isset(Yii::$app->request->queryParams['user_name'])) {
            unset($actions['index']);
        }
        return $actions;
    }

    public function actionIndex($user_name) {
        $user_profile = User::findOne(['user_name' => $user_name]);
        return ['user' => $user_profile];
    }

}
