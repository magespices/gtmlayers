<?php
/**
 * Created by Q-Solutions Studio
 * Date: 15.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Plugin\CustomerData;

use Magento\Checkout\CustomerData\AbstractItem;
use Magento\Quote\Model\Quote\Item;
use Magespices\GTMDataLayers\Helper\Data;

/**
 * Class ExtendProductData
 * @package Magespices\GTMDataLayers\Plugin\CustomerData
 */
class ExtendProductData
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * ExtendProductData constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param AbstractItem $subject
     * @param array $result
     * @param Item $item
     * @return array
     */
    public function afterGetItemData(AbstractItem $subject, array $result, Item $item): array
    {
        $product = $this->helper->getProduct($result['product_id']);

        if ($product) {
            $result['producent'] = $product->getAttributeText('producent') ?? '';
            $result['identifier'] = $this->helper->getProductIdentifier($product->getId()) ?? '';
            $result['categories'] = $this->helper->getCategoriesAsString($product->getCategoryIds()) ?? '';
            $result['product_price_currency_code'] = $this->helper->getCurrencyCode() ?? '';
        }

        return $result;
    }
}
