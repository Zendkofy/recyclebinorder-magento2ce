<?php

/**
 * Zendkofy
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Zendkofy
 * @package     Zendkofy_RecycleBinOrder
 * @copyright   Copyright © 2016 Zendkofy. All rights reserved.
 */

namespace Zendkofy\RecycleBinOrder\UiComponent\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class OrderTrashActions extends Column
{
    /**
     * Url path
     */
    const URL_PATH_DELETE = 'recyclebinadmin/trash/delete';
    const URL_PATH_VIEW = 'sales/order/view';
    const URL_PATH_RESTORE = 'recyclebinadmin/trash/restore';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $items
     *
     * @return array
     */
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'view'    => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_VIEW,
                                [
                                    'order_id' => $item['entity_id'],
                                ]
                            ),
                            'label' => __('View'),
                        ],
                        'restore' => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_RESTORE,
                                [
                                    'order_id' => $item['entity_id'],
                                ]
                            ),
                            'label' => __('Restore'),
                        ],
                        'delete'  => [
                            'href'    => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'order_id' => $item['entity_id'],
                                ]
                            ),
                            'label'   => __('Delete'),
                            'confirm' => [
                                'title'   => __('Delete order "${ $.$data.increment_id }"'),
                                'message' => __('Are you sure you wan\'t to delete order "${ $.$data.increment_id }"? The order will be really deleted and you cannot restore it!'),
                            ],
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}