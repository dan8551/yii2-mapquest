<?php

namespace dan8551\components\mapquest;

use Yii;
use yii\web\AssetBundle;

class MapQuestAsset extends AssetBundle
{
     public $sourcePath = '@vendor/dan8551/yii2-mapquest/assets';

    public $depends = [
        'yii\web\YiiAsset',
    ];
   
   public $css = [
	'css/mapquest.css'
   ];
    
    public $js = [
        'js/mapquest.js',
    ];


}
