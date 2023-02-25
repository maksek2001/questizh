<?php

namespace app\assets;

use yii\web\AssetBundle;

class QuestAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/sweetalert2/sweetalert2.css',
        'css/progress-points.css',
        'css/scroll.css',
        'css/site.css',
        'css/quest.css'
    ];
    public $js = [
        'vendor/sweetalert2/sweetalert2.js',
        'js/popup.js',
        'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
