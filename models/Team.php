<?php

namespace app\models;

use yii\base\NotSupportedException;
use Yii;

/**
 * Команда
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $status
 * @property string $email
 * 
 */
class Team extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';

    public static function tableName()
    {
        return '{{teams}}';
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
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findByUsername(string $username)
    {
        return static::find()->where(['username' => $username, 'status' => self::STATUS_ACTIVE])->one();
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public static function existUsername(string $username): bool
    {
        return static::find()->where(['username' => $username])->count() > 0;
    }

    public static function existEmail(string $email): bool
    {
        return static::find()->where(['email' => $email])->count() > 0;
    }

    public static function existName(string $name): bool
    {
        return static::find()->where(['name' => $name])->count() > 0;
    }
}
