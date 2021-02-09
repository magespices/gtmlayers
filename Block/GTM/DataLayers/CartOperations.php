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

use Magento\Framework\Exception\NoSuchEntityException;
use Magespices\GTMDataLayers\Block\AbstractBlock;
use Magespices\GTMDataLayers\Helper\Data;

/**
 * Class CartOperations
 * @package Magespices\GTMDataLayers\Block\GTM\DataLayers
 */
class CartOperations extends AbstractBlock
{
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        if ((bool)$this->helper->getConfigValue(Data::GTMDATALAYERS_LAYERS_CART_OPERATIONS_ENABLED_XPATH)) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getStoreCurrencyCode(): string
    {
        try {
            return $this->_storeManager->getStore()->getCurrentCurrencyCode();
        } catch (\Exception $exception) {
            return '';
        }
    }
}
