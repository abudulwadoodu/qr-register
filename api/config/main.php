<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
//            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
//                $response->format = $event->sender->format;

                if ($response->data !== null && $response->format == 'json') { //} && Yii::$app->request->get('suppress_response_code')) {
                    $data_index = (is_array(Yii::$app->controller->serializer) && Yii::$app->controller->serializer['collectionEnvelope']) ? Yii::$app->controller->serializer['collectionEnvelope'] : 'items';

                    $meta = [
                        'status' => $response->isSuccessful,
                        'has_content' => (bool) count(isset($response->data[$data_index]) ? $response->data[$data_index] : $response->data),
                        'message' => ''
                    ];

                    if (isset($response->data['meta_message'])) {
                        $meta['message'] = $response->data['meta_message'];
                        unset($response->data['meta_message']);
                    }

                    if ($response->isSuccessful) {
                        /* if(!isset($response->data['items']) && !isset($response->data['meta_response_index']))
                          $response->data = [Yii::$app->params['rest']['single-envelope'] => $response->data];
                          else */
                        if (isset($response->data['meta_response_index']))
                            unset($response->data['meta_response_index']);

                        $response->data = is_array($response->data) ? array_merge(['meta' => $meta], $response->data) : ['meta' => $meta];
                    } else {
                        if ($response->statusCode == 422)
                            $meta['message'] = 'Validation error';

                        $response->data = [
                            'meta' => $meta,
                            'errors' => $response->data
                        ];
                    }
                    Yii::info($response->data);
                }
            },
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/country',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/user',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]

                ]
            ],        
        ]
    ],
    'params' => $params,
];



