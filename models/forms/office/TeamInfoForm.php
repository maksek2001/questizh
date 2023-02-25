<?php

namespace app\models\forms\office;

use Yii;
use app\models\Team;
use yii\base\Model;

class TeamInfoForm extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function attributeLabels()
    {
        return [
            'name' => 'Название команды',
            'email' => 'E-mail'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['name', 'validateName'],
            ['email', 'validateEmail']
        ];
    }

    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $teamId = Team::findOne(['email' => $this->email])->id;
            if ($teamId != null && $teamId != Yii::$app->user->id) {
                $this->addError($attribute, 'Данный e-mail занят');
            }
        }
    }

    public function validateName($attribute)
    {
        if (!$this->hasErrors()) {
            $teamId = Team::findOne(['name' => $this->name])->id;
            if ($teamId != null && $teamId != Yii::$app->user->id) {
                $this->addError($attribute, 'Данное название команды занято');
            }
        }
    }

    public function updateInfo(): bool
    {
        if (!$this->validate())
            return false;

        $team = Team::findOne(Yii::$app->user->id);

        $team->name = $this->name;
        $team->email = $this->email;

        return $team->save();
    }
}
