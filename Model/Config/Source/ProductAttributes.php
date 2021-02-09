<?php
/**
 * Created by Q-Solutions Studio
 * Date: 09.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ProductAttributes
 * @package Magespices\GTMDataLayers\Model\Config\Source
 */
class ProductAttributes implements OptionSourceInterface
{
    /**
     * @var AttributeCollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * ProductAttributes constructor.
     * @param AttributeCollectionFactory $attributeCollectionFactory
     */
    public function __construct(AttributeCollectionFactory $attributeCollectionFactory)
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [
            [
                'value' => 'sku',
                'label' => 'SKU'
            ],
            [
                'value' => 'entity_id',
                'label' => 'ID'
            ]
        ];
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection->addIsFilterableFilter();

        /** @var Attribute $item */
        foreach ($attributeCollection->getItems() as $item) {
            $options[] = [
                'value' => $item->getAttributeCode(),
                'label' => $item->getDefaultFrontendLabel()
            ];
        }

        return $options;
    }
}
