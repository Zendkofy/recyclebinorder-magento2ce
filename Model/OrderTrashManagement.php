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

namespace Zendkofy\RecycleBinOrder\Model;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Zendkofy\RecycleBinOrder\Api\OrderTrashManagementInterface;

class OrderTrashManagement implements OrderTrashManagementInterface
{
    /**
     * @var OrderTrashFactory
     */
    protected $_orderTrashFactory;

    /**
     * @var ResourceModel\OrderTrash\CollectionFactory
     */
    protected $_orderTrashCollectionFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var ResourceModel\OrderTrash
     */
    protected $_orderTrashResource;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * OrderTrashManagement constructor.
     *
     * @param OrderTrashFactory $orderTrashFactory
     */
    public function __construct(
        OrderTrashFactory $orderTrashFactory,
        ResourceModel\OrderTrash\CollectionFactory $orderTrashCollectionFactory,
        \Zendkofy\RecycleBinOrder\Model\ResourceModel\OrderTrash $orderTrashResource,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
    ) {
        $this->_orderTrashFactory = $orderTrashFactory;
        $this->_orderTrashCollectionFactory = $orderTrashCollectionFactory;
        $this->_orderTrashResource = $orderTrashResource;
        $this->_orderFactory = $orderFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function isOrderInTrash($orderId)
    {
        return (bool)$this->_orderTrashCollectionFactory->create()
            ->addFieldToFilter('order_id', $orderId)
            ->getSize();
    }

    /**
     * @return int[]
     */
    public function getOrderIdsInTrash()
    {
        /** @var ResourceModel\OrderTrash\Collection $collection */
        $collection = $this->_orderTrashCollectionFactory->create();
        $collection->addFieldToSelect('order_id');

        return $collection->getColumnValues('order_id');
    }

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function moveOrderToTrash($orderId)
    {
        $collection = $this->_orderCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', $orderId);

        return $this->moveOrdersToTrash($collection);
    }

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     *
     * @return array
     */
    public function moveOrdersToTrash(
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection
    ) {
        $orderIds = $collection->getAllIds();

        $this->_orderTrashResource->moveOrdersToTrash(
            $orderIds
        );

        return $orderIds;
    }

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function restoreOrder($orderId)
    {
        $collection = $this->_orderCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', $orderId);

        return $this->restoreOrders($collection);
    }

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     *
     * @return array
     */
    public function restoreOrders(
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection
    ) {
        $orderIds = $collection->getAllIds();

        $this->_orderTrashResource->restoreOrders($orderIds);

        return $orderIds;
    }

    /**
     * @param mixed $orderIds
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteOrders($orderIds)
    {
        $orderIds = is_array($orderIds) ? $orderIds : [$orderIds];

        $collection = $this->_orderCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        $orderIds = $collection->getAllIds();
        $this->_orderTrashResource->deleteOrders($orderIds);

        return $orderIds;
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return AbstractCollection
     */
    public function filterOrderGridCollection(
        AbstractCollection $collection
    ) {
        $orderIdsInTrash = $this->getOrderIdsInTrash();

        if (!empty($orderIdsInTrash)) {
            $collection->addFieldToFilter(
                'entity_id',
                ['nin' => $orderIdsInTrash]
            );
        }

        return $collection;
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return AbstractCollection
     */
    public function filterInvoiceGridCollection(
        AbstractCollection $collection
    ) {
        $collection->addFieldToFilter('entity_id', [
            'nin' => $this->_orderTrashResource->getSelectEntityIdByOrderIds(
                'sales_invoice',
                $this->getOrderIdsInTrash()
            ),
        ]);

        return $collection;
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return AbstractCollection
     */
    public function filterCreditmemoGridCollection(
        AbstractCollection $collection
    ) {
        $collection->addFieldToFilter('entity_id', [
            'nin' => $this->_orderTrashResource->getSelectEntityIdByOrderIds(
                'sales_creditmemo',
                $this->getOrderIdsInTrash()
            ),
        ]);

        return $collection;
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return AbstractCollection
     */
    public function filterShipmentGridCollection(
        AbstractCollection $collection
    ) {
        $collection->addFieldToFilter('entity_id', [
            'nin' => $this->_orderTrashResource->getSelectEntityIdByOrderIds(
                'sales_shipment',
                $this->getOrderIdsInTrash()
            ),
        ]);

        return $collection;
    }

    /**
     * @return mixed
     */
    public function clearTrash()
    {
        $orderIdsInTrash = $this->getOrderIdsInTrash();
        if (!empty($orderIdsInTrash)) {
            $this->deleteOrders($orderIdsInTrash);
        }

        return true;
    }
}