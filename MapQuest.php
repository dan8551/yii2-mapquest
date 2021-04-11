<?php

namespace dan8551\components\mapquest;

use yii\bootstrap4\Html;
use yii\web\View;
use \yii\base\Widget;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Creates a map using MapQuest and displays the map centered at the lat/long provided.
 */
class MapQuest extends Widget
{
    /**
     * The type of map to be displayed.
     * This displays the map as a default map image
     */
    const MAP_TYPE_MAP = 0;
    
    /**
     * The type of map to be displayed.
     * This displays the map as a hybrid map with satellite image laid over the default map.
     */
    const MAP_TYPE_HYBRID = 1;
    
    /**
     * The type of map to be displayed.
     * This displays the map as a satellite map
     */
    const MAP_TYPE_SATELLITE = 2;
    
    /**
     * The type of map to be displayed.
     * This displays the default map in light grey scale
     */
    const MAP_TYPE_LIGHT = 3;
    
    /**
     * The type of map to be displayed.
     * This displays the default map in dark grey scale.
     */
    const MAP_TYPE_DARK = 4;
    
    /**
     * 
     * @var string ID of the container where the map is to be placed
     */
    public $containerId;
    
    /**
     * 
     * @var string MapQuest API key
     * See https://developer.mapquest.com/ to get a key
     */
    public $apiKey;

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
     * 
     * @var int Type of map to be displayed
     */
    public $mapType = self::MAP_TYPE_MAP;
    
    /**
     * 
     * @var int level of zoom to be applied by default
     */
    public $zoomLevel = 16;
    
    /**
     * 
     * @var string ID of the map object created in JavaScript
     */
    public $jsObjectId;
    
    /**
     * Initializes the Widget
     * This method will initialize required property values.
     */
    public function init()
    {
        parent::init();
        if($this->containerId === null)
            throw new InvalidConfigException('Please specify the "containerId" property.');
        if($this->apiKey === null)
            throw new InvalidConfigException('Please specify the "apiKey" property');
        if($this->latitude === null)
            throw new InvalidConfigException('Please specify the "latitude" property');
        if($this->longitude === null)
            throw new InvalidConfigException('Please specify the "longitude" property');
    }
    
    /**
     * Overrides the parent function to load remote view into modal via Ajax.
     */
    public function run()
    {
	parent::run();
        $this->registerAssets();
    }
    
    public function setMapType()
    {
        switch($this->mapType)
        {
            case self::MAP_TYPE_MAP:
                return 'map';
            case self::MAP_TYPE_SATELLITE:
                return 'satellite';
            case self::MAP_TYPE_HYBRID:
                return 'hybrid';
            case self::MAP_TYPE_LIGHT:
                return 'light';
            case self::MAP_TYPE_DARK:
                return 'dark';
            /**
             * In case other maps get added then use this functionality to 
             * ensure it can still be used before an update is released
             */
            default:                    
                return $this->mapType;
        }
    }
    
    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        MapQuestAsset::register($view);
        if($this->jsObjectId === null)
            $this->jsObjectId = 'map'.uniqid();
        
        $js = <<<JS
            L.mapquest.key = '{$this->apiKey}';
            var {$this->jsObjectId} = L.mapquest.map('{$this->containerId}', {
                center: [{$this->latitude}, {$this->longitude}],
                layers: L.mapquest.tileLayer('map'),
                zoom: {$this->zoomLevel},
                layers: L.mapquest.tileLayer('{$this->setMapType()}'),
              });
        JS;
        $view->registerJs($js,View::POS_READY);
    }
}

?>
