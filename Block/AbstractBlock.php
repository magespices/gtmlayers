<?php
/**
 * Created by Q-Solutions Studio
 * Date: 09.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magespices\GTMDataLayers\Helper\Data;

/**
 * Class AbstractBlock
 * @package Magespices\GTMDataLayers\Block
 */
abstract class AbstractBlock extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * AbstractBlock constructor.
     * @param Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        if ($this->getGTMIdentifier()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * @return string|null
     */
    public function getGTMIdentifier(): ?string
    {
        return $this->helper->getGTMIdentifier();
    }

    /**
     * @param int|null $productId
     * @return Product|null
     */
    public function getProduct(int $productId = null): ?Product
    {
        return $this->helper->getProduct($productId);
    }

    /**
     * @param int|null $productId
     * @return string
     */
    public function getProductIdentifier(int $productId = null): string
    {
        return $this->helper->getProductIdentifier($productId);
    }

    /**
     * @return string
     */
    public function getProductBrandField(): string
    {
        return $this->helper->getProductBrandField();
    }

    /**
     * @param array $categoryIds
     * @param string $glue
     * @return string
     */
    public function getCategoriesAsString(array $categoryIds, string $glue = '|'): string
    {
        return $this->helper->getCategoriesAsString($categoryIds, $glue);
    }

    /**
     * @param float $price
     * @return string
     */
    public function formatPrice(float $price): string
    {
        return number_format($price, 2, '.', '');
    }
}
