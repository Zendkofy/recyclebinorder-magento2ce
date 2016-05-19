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

namespace Zendkofy\RecycleBinOrder\UiComponent\DataProvider;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Zendkofy\RecycleBinOrder\Api\OrderTrashManagementInterface;

class SearchResult extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var OrderTrashManagementInterface
     */
    protected $_orderTrashManagement;
    
    protected $_orderTrashConfigManagement;

    /**
     * SearchResult constructor.
     *
     * @param EntityFactory $entityFactory
     * @param Logger        $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager  $eventManager
     * @param string        $mainTable
     * @param string        $resourceModel
     * @param string        $eventPrefix
     * @param string        $eventObject
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        OrderTrashManagementInterface $orderTrashManagement,
        \Zendkofy\RecycleBinOrder\Helper\Config\ConfigManagement $orderTrashConfigManagement,
        $mainTable,
        $resourceModel,
        $eventPrefix = '',
        $eventObject = ''
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel
        );

        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_orderTrashManagement = $orderTrashManagement;
        $this->_orderTrashConfigManagement = $orderTrashConfigManagement;
    }
}