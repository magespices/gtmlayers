<?php
/**
 * Created by Q-Solutions Studio
 * Date: 09.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Block\GTM\DataLayers;

use Magespices\GTMDataLayers\Block\AbstractBlock;
use Magespices\GTMDataLayers\Helper\Data;

/**
 * Class ProductDetails
 * @package Magespices\GTMDataLayers\Block\GTM\DataLayers
 */
class ProductDetails extends AbstractBlock
{
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        if ((bool)$this->helper->getConfigValue(Data::GTMDATALAYERS_LAYERS_PRODUCT_DETAILS_ENABLED_XPATH)
            && $this->getProduct()) {
            return parent::_toHtml();
        }
        return '';
    }
}
