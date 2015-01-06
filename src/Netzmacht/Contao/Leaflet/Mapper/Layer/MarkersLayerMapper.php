<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\Mapper\Layer;

use Netzmacht\Contao\Leaflet\Mapper\DefinitionMapper;
use Netzmacht\Contao\Leaflet\Mapper\GeoJsonMapper;
use Netzmacht\Contao\Leaflet\Model\MarkerModel;
use Netzmacht\LeafletPHP\Definition;
use Netzmacht\LeafletPHP\Definition\GeoJson\FeatureCollection;
use Netzmacht\LeafletPHP\Definition\Group\LayerGroup;
use Netzmacht\LeafletPHP\Definition\Type\LatLngBounds;
use Netzmacht\LeafletPHP\Definition\UI\Marker;
use Netzmacht\LeafletPHP\Plugins\Ajax\GeoJsonAjax;

class MarkersLayerMapper extends AbstractLayerMapper implements GeoJsonMapper
{
    /**
     * Class of the definition being created.
     *
     * @var string
     */
    protected static $definitionClass = 'Netzmacht\LeafletPHP\Definition\Group\GeoJson';

    /**
     * Layer type.
     *
     * @var string
     */
    protected static $type = 'markers';

    /**
     * {@inheritdoc}
     */
    protected function createInstance(\Model $model, DefinitionMapper $mapper, LatLngBounds $bounds = null)
    {
        if ($model->deferred) {
            $reflector = new \ReflectionClass('Netzmacht\LeafletPHP\Plugins\Ajax\GeoJsonAjax');
            $instance  = $reflector->newInstanceArgs($this->buildConstructArguments($model, $mapper, $bounds));

            return $instance;
        }

        return parent::createInstance($model, $mapper, $bounds);
    }

    /**
     * {@inheritdoc}
     */
    protected function doBuild(
        Definition $definition,
        \Model $model,
        DefinitionMapper $builder,
        LatLngBounds $bounds = null
    ) {
        if ($definition instanceof GeoJsonAjax) {
            $base = \Config::get('websitePath') . '/system/modules/leaflet/public/geojson.php?id=';
            $definition->setUrl($base . $model->id);
        } elseif ($definition instanceof LayerGroup) {
            $collection = $this->loadMarkerModels($model);

            if ($collection) {
                foreach ($collection as $item) {
                    $definition->addLayer($this->createMarker($item));
                }
            }
        }
    }

    /**
     * @param \Model           $model
     * @param DefinitionMapper $mapper
     * @param LatLngBounds     $bounds
     *
     * @return mixed
     */
    public function handleGeoJson(\Model $model, DefinitionMapper $mapper, LatLngBounds $bounds = null)
    {
        $feature    = new FeatureCollection();
        $collection = $this->loadMarkerModels($model);

        if ($collection) {
            foreach ($collection as $item) {
                $feature->addFeature($this->createMarker($item)->getFeature());
            }
        }

        return $feature;
    }

    /**
     * @param $item
     *
     * @return Marker
     */
    protected function createMarker($item)
    {
        $marker = new Marker('marker_' . $item->id, $item->coordinates);
        $marker->setTitle($item->tooltip);

        return $marker;
    }

    /**
     * @param \Model $model
     *
     * @return \Model\Collection|null
     */
    protected function loadMarkerModels(\Model $model)
    {
        $collection = MarkerModel::findBy(
            array('active=1', 'pid=?'),
            array($model->id)
        );

        return $collection;
    }
}
