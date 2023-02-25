<?php

namespace app\models\forms\authentication;

use Yii;
use yii\base\Model;
use app\models\Team;

class SignupForm extends Model
{
    /** @var string */
    public $username;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'name' => 'Название команды',
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];
    }

    public function rules()
    {
        return [
            [['username', 'name', 'password', 'email'], 'required', 'message' => 'Обязательное поле!'],
            [['username', 'password', 'name'], 'string', 'length' => [1, 20], 'tooLong' => 'Слишком длинный', 'tooShort' => 'Слишком короткий'],
            ['email', 'email', 'message' => 'Некорректный E-mail'],
            ['username', 'match', 'pattern' => '/^[a-z0-9-_|!|.|@|#|&]*$/i', 'message' => 'Введён недопустимый символ. Допустимые символы a-z 0-9 _'],
            ['password', 'match', 'pattern' => '/^[a-z0-9-_|!|.|@|#|&]*$/i',  'message' => 'Введён недопустимый символ. Допустимые символы a-z 0-9 _ ! @ # &'],
            ['name', 'validateName'],
            ['email', 'validateEmail'],
            ['username', 'validateLogin']
        ];
    }

    public function validateLogin($attribute)
    {
        if (!$this->hasErrors()) {
            if (Team::existUsername($this->username)) {
                $this->addError($attribute, 'Данный логин уже занят');
            }
        }
    }

    public function validateName($attribute)
    {
        if (!$this->hasErrors()) {
            if (Team::existName($this->name)) {
                $this->addError($attribute, 'Данное название команды занято');
            }
        }
    }

    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            if (Team::existEmail($this->email)) {
                $this->addError($attribute, 'Данный e-mail занят');
            }
        }
    }

    public function signup(): bool
    {
        if (!$this->validate())
            return false;
            
        $team = new Team();

        $team->name = $this->name;
        $team->email = $this->email;
        $team->username = $this->username;
        $team->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $team->generateAuthKey();

        return $team->save(false);
    }
}
