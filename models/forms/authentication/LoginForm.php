<?php

namespace app\models\forms\authentication;

use Yii;
use yii\base\Model;
use app\models\Team;

class LoginForm extends Model
{
    const REMEMBER_TIME = 3600 * 24;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var bool */
    public $rememberMe = false;

    /** @var Team */
    private $_user = null;

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Обязательное поле!'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $team = $this->getTeam();

            if (!$team || !$team->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getTeam(),  $this->rememberMe ? self::REMEMBER_TIME : 0);
        }
        return false;
    }

    public function getTeam(): ?Team
    {
        if ($this->_user === null) {
            $this->_user = Team::findByUsername($this->username);
        }

        return $this->_user;
    }
}
