<?php

namespace app\assets;

use yii\web\AssetBundle;

class RatingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/sweetalert2/sweetalert2.css',
        'css/scroll.css',
        'css/site.css',
        'css/rating.css'
    ];
    public $js = [
        'vendor/sweetalert2/sweetalert2.js',
        'vendor/jquery/jquery.viewport.js',
        'js/popup.js',
        'js/main.js',
        'js/rating.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
