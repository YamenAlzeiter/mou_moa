<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'pgsql:host=localhost;dbname=moua',
            'username' => 'postgres',
            'password' => 'admin',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com', // Gmail SMTP server
                'username' => 'test24testy24test@gmail.com', // Your Gmail email address
                'password' => 'lbst ieyq ydqb jlvp ', // Your Gmail password or app-specific password
                'port' => 587, // Port for SSL, or 587 for TLS
                'encryption' => 'tls', // Use 'ssl' for SSL, 'tls' for TLS, or remove this line for no encryption
                'scheme' => 'smtp',
            ],
        ],
    ],
];
