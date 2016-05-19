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

namespace Zendkofy\RecycleBinOrder\Api;

interface OrderTrashManagementInterface
{
    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function isOrderInTrash($orderId);

    /**
     * @return int[]
     */
    public function getOrderIdsInTrash();

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function moveOrderToTrash($orderId);

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     *
     * @return array
     */
    public function moveOrdersToTrash(
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection
    );

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function restoreOrder($orderId);

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     *
     * @return array
     */
    public function restoreOrders(
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection
    );

    /**
     * @param $orderIds
     *
     * @return mixed
     */
    public function deleteOrders($orderIds);


    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function filterOrderGridCollection(
        \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
    );

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function filterInvoiceGridCollection(
        \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
    );

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function filterCreditmemoGridCollection(
        \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
    );

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function filterShipmentGridCollection(
        \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
    );

    /**
     * @return mixed
     */
    public function clearTrash();
}