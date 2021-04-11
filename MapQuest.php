<?php

namespace dan8551\components\mapquest;

use yii\bootstrap4\Html;
use yii\web\View;
use \yii\base\Widget;
use Yii;

/**
 * Creates a map using MapQuest and displays the map centered at the lat/long provided.
 */
class MapQuest extends Widget
{
    /**
     * 
     * @var string MapQuest API key
     * See https://developer.mapquest.com/ to get a key
     */
    public $apiKey = '';

    /**
     * 
     * @var float latitude of location to be displayed
     */
    public $latitude;
    
    /**
     * 
     * @var float longitude of location to be displayed
     */
    public $longitude;
    
    /**
     * Overrides the parent function to load remote view into modal via Ajax.
     */
    public function run()
    {
	parent::run();
        Html::tag('div', '', ['id' => 'map']);
    }
    
    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        MapQuestAsset::register($view);
        $uuid = uniqid();
        $js = <<<JS
            L.mapquest.key = {$this->apiKey};
            L.mapquest.map('map', {
                center: [{$this->latitude}, {$this->longitude}],
                layers: L.mapquest.tileLayer('map'),
                zoom: 12
              });
        JS;
        $view->registerJs($js,View::POS_READY);
    }
}

?>
