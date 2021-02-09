<?php
/**
 * Created by Q-Solutions Studio
 * Date: 10.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Block\GTM\DataLayers;

use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magespices\GTMDataLayers\Block\AbstractBlock;
use Magespices\GTMDataLayers\Helper\Data;

/**
 * Class CheckoutSuccess
 * @package Magespices\GTMDataLayers\Block\GTM\DataLayers
 */
class CheckoutSuccess extends AbstractBlock
{
    /**
     * @var CheckoutSessionFactory
     */
    protected $checkoutSessionFactory;

    /**
     * @var Configuration
     */
    protected $configurationHelper;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * CheckoutSuccess constructor.
     * @param Context $context
     * @param Data $helper
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param Configuration $configurationHelper
     * @param Json $serializer
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        CheckoutSessionFactory $checkoutSessionFactory,
        Configuration $configurationHelper,
        Json $serializer,
        array $data = []
    ) {
        $this->checkoutSessionFactory = $checkoutSessionFactory;
        $this->configurationHelper = $configurationHelper;
        $this->serializer = $serializer;
        parent::__construct($context, $helper, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        if ((bool)$this->helper->getConfigValue(Data::GTMDATALAYERS_LAYERS_PURCHASE_ENABLED_XPATH)) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        $checkoutSession = $this->checkoutSessionFactory->create();
        return $checkoutSession->getLastRealOrder();
    }

    /**
     * @return string
     */
    public function getAffiliationValue(): string
    {
        return $this->helper->getConfigValue(Data::GTMDATALAYERS_GENERAL_AFFILIATION_XPATH);
    }

    /**
     * @param Item $item
     * @return string
     */
    public function getProductOptionsAsString(Item $item): string
    {
        $string = '';
        $result = [];

        if (!$item->getParentItem()) {
            return $string;
        }

        if ($item->getParentItem()->getProductType() === Type::TYPE_BUNDLE) {
            if ($item instanceof Item) {
                $options = $item->getProductOptions();
            } else {
                $options = $item->getOrderItem()->getProductOptions();
            }
            if (isset($options['bundle_selection_attributes'])) {
                $options = $this->serializer->unserialize($options['bundle_selection_attributes']);
                return $options['option_label'];
            }
        }

        if ($options = $item->getParentItem()->getProductOptions()) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (!empty($options['attributes_info'])) {
                $result = array_merge($options['attributes_info'], $result);
            }
        }

        $lastItem = end($result);
        foreach ($result as $option) {
            $string = sprintf('%s: %s', $option['label'], $option['value']);
            if ($option !== $lastItem) {
                $string .= ',';
            }
        }

        return $string;
    }
}
