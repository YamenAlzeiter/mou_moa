<?php
namespace common\models;

use phpCAS;
use Yii;
use yii\base\Model;
use yii\helpers\Url;


/**
 * Login form
 */
class LoginCas extends Model
{
    private $_user;
    // public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->casAuthenticate();
        // echo json_encode($user);
        // exit;
        if ($user) {
            $user = $this->getUser($user);

            return Yii::$app->user->login($user, false ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser($user)
    {
        // $user = $this->casAuthenticate();
        if ($this->_user === null) {
            $this->_user = User::findByUsername($user);
        }

        return $this->_user;
    }

    public function casAuthenticate()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");

        $params = Yii::$app->params['cas'];
        $host = $params['host'];
        $port = $params['port'];
        $uri = $params['uri'];
        $filename = $params['log_file'];
        $ssl = $params['ssl'];
        //if (!phpCAS::isAuthenticated()) {
        phpCAS::setDebug($filename);
        phpCAS::setVerbose(false);
        phpCAS::client(SAML_VERSION_1_1, $host, $port, $uri);
        phpCAS::setCasServerCACert($ssl);

        phpCAS::forceAuthentication();
        $usernameCas = phpCAS::getUser();
        $userattributes = phpCAS::getAttributes();

        $usertype = $userattributes['userType'];
        $type = explode(':', $usertype);

        $user = $this->getUser($usernameCas);

        $con = \Yii::$app->db;

        if (!isset($user) && ($type[0] == 'STAFF')){
            $attributes = [
                'username' => $usernameCas,
                'email' => $userattributes['mail'],
                'auth_key' => Yii::$app->security->generateRandomString(),
                'status' => '10',
                'created_at' => date('Ymd'),
                // 'updated_at' => date('YmdHis'),
                'role' => 'Guest',
                'remarks' => 'Guest - First Time Login',
            ];
            $con->createCommand()->insert('user', $attributes)->execute();

            $user = User::findByUsername($usernameCas);
            // echo  $user->getId();
            // exit;
            // $id = $user->id;
            // Yii::$app->authManager->assign('Guest', $id);

            $auth = \Yii::$app->authManager;
            $authorRole = $auth->getRole('Guest');
            $auth->assign($authorRole, $user->getId());
        }

        $attributex = [
            'username' => $usernameCas,
            'login_datetime' => date('Y-m-d H:i:s'),
            'staff_id' => $userattributes['staffId'],
            'job_title' => $userattributes['jobTitle'],
            'kcdio' => $userattributes['kcdi'],
            'user_type' => $userattributes['userType'],
            'email' => $userattributes['mail'],
        ];
        $con->createCommand()->insert('user_log', $attributex)->execute();


        return phpCAS::getUser();

    }

    /*public function casLogout() {
        $params = Yii::$app->params['cas'];
        $host = $params['host'];
        $port = $params['port'];
        $uri = $params['uri'];
        $filename = $params['log_file'];
        $ssl = $params['ssl'];

        phpCAS::setDebug($filename);
        phpCAS::setVerbose(false);
        phpCAS::client(SAML_VERSION_1_1, $host, $port, $uri);
        phpCAS::setCasServerCACert($ssl);
        phpCAS::handleLogoutRequests(true, $host );
        session_start();

        return phpCAS::logout();
    }*/

    public function casLogout()
    {
        $params = Yii::$app->params['cas'];
        $host = $params['host'];
        $port = $params['port'];
        $uri = $params['uri'];
        $filename = $params['log_file'];
        $ssl = $params['ssl'];
        //if (!phpCAS::isAuthenticated()) {
        phpCAS::setDebug($filename);
        phpCAS::setVerbose(true);
        phpCAS::client(SAML_VERSION_1_1, $host, $port, $uri);
        phpCAS::setCasServerCACert($ssl);
        if (phpCAS::isAuthenticated()) {
            phpCAS::logout(['service' => Url::home(true)]);
        }
        return $this->redirect(Url::home(true));
    }

}