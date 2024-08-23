<?php

use yii\db\Migration;

class m210101_000001_init_rbac extends Migration
{
    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create roles
        $osic = $auth->createRole('OSIC');
        $io = $auth->createRole('IO');
        $oil = $auth->createRole('OIL');
        $rmc = $auth->createRole('RMC');
        $ola = $auth->createRole('OlA');
        $auth->add($osic);
        $auth->add($io);
        $auth->add($oil);
        $auth->add($rmc);
        $auth->add($ola);

        // Create admin user
        $adminUser = new \common\models\User();
        $adminUser->username = '1725635';
        $adminUser->email = 'alzeiter.yamen@live.iium.edu.my';
        $adminUser->type = 'KICT';
        $adminUser->generateAuthKey();
        $adminUser->status = \common\models\User::STATUS_ACTIVE;
        if ($adminUser->save()) {
            $auth->assign($osic, $adminUser->id);
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
