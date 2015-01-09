<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\Mapper;

use Netzmacht\LeafletPHP\Definition;
use Netzmacht\LeafletPHP\Definition\Type\LatLngBounds;

interface Mapper
{
    /**
     * Map model to the definition.
     *
     * @param \Model|mixed     $model     The model being built. Usually a contao model. but can be anything
     * @param DefinitionMapper $mapper    The definition builder.
     * @param LatLngBounds     $bounds    Optional bounds where elements should be in.
     * @param string           $elementId Optional element id.
     *
     * @return Definition
     */
    public function handle($model, DefinitionMapper $mapper, LatLngBounds $bounds = null, $elementId = null);

    /**
     * Check if mapper is responsible for the model.
     *
     * @param \Model       $model  The model being build.
     * @param LatLngBounds $bounds Optional bounds where elements should be in.
     *
     * @return bool
     */
    public function match($model, LatLngBounds $bounds = null);
}
