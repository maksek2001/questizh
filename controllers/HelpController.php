<?php

namespace app\controllers;

class HelpController extends SiteController
{
    public $layout = 'help';

    public function actionQuest()
    {
        return $this->render('quest');
    }
}
