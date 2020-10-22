<?php
return [
    'adminEmail' => 'admin@example.com',
    'serializer' => [
        'class' => 'yii\rest\Serializer',
        'metaEnvelope' => 'pagination',
        'collectionEnvelope' => 'items'
    ]
];
