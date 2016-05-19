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

namespace Zendkofy\RecycleBinOrder\Model\ResourceModel\OrderTrash\Order\Grid;

use Magento\Framework\Api;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Zendkofy\RecycleBinOrder\Api\OrderTrashManagementInterface;
use Zendkofy\RecycleBinOrder\Setup\InstallSchema;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var OrderTrashManagementInterface
     */
    protected $_orderTrashManagement;

    /**
     * Collection constructor.
     *
     * @param EntityFactory                 $entityFactory
     * @param Logger                        $logger
     * @param FetchStrategy                 $fetchStrategy
     * @param EventManager                  $eventManager
     * @param OrderTrashManagementInterface $orderTrashManagement
     * @param string                        $mainTable
     * @param                               $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        OrderTrashManagementInterface $orderTrashManagement,
        $mainTable,
        $resourceModel
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel
        );

        $this->_orderTrashManagement = $orderTrashManagement;
    }

    public function _construct()
    {
        parent::_construct();
        $this->_map['fields']['trash_date'] = 'order_trash.trash_date';
    }


    public function _renderFiltersBefore()
    {
        $this->addFieldToFilter(
            'entity_id',
            ['in' => $this->_orderTrashManagement->getOrderIdsInTrash()]
        );

        $this->getSelect()->joinRight(
            ['order_trash' => $this->getTable(InstallSchema::SCHEMA_ORDER_TRASH)],
            'main_table.entity_id = order_trash.order_id',
            ['trash_date' => 'order_trash.trash_date']
        );

        parent::_renderFiltersBefore();
    }
}