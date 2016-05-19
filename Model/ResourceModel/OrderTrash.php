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

namespace Zendkofy\RecycleBinOrder\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;

/**
 * Resource Model OrderTrash
 */
class OrderTrash extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @var Resource|Resource
     */
    protected $_resource;

    /**
     * OrderTrash constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param Resource                                          $resource
     * @param null                                              $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Zendkofy\RecycleBinOrder\Model\ResourceModel\Resource $resource,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_resource = $resource;
    }

    /**
     * @param array $orderIds
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function moveOrdersToTrash(array $orderIds = [])
    {
        if (empty($orderIds)) {
            return $this;
        }

        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), 'order_id')
            ->where('order_id IN(?)', $orderIds);

        $old = $this->getConnection()->fetchCol($select);

        $this->_resource->insertData(
            $this->getMainTable(),
            ['order_id'],
            array_diff($orderIds, $old)
        );

        return $this;
    }

    /**
     * @param array $orderIds
     */
    public function restoreOrders(array $orderIds = [])
    {
        $this->_resource->deleteData(
            $this->getMainTable(),
            ['order_id IN(?)' => $orderIds]
        );

        return $this;
    }

    /**
     * @param $orderIds
     */
    public function deleteOrders(array $orderIds = [])
    {
        $connection = $this->getConnection();
        $connection->startSetup();

        $this->beginTransaction();

        try {
            $this->_deleteQuoteData($orderIds);
            $this->_deleteCreditmemoData($orderIds);
            $this->_deleteInvoiceData($orderIds);
            $this->_deleteShipmentData($orderIds);
            $this->_deleteOrderData($orderIds);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw new LocalizedException(__($e->getMessage()), $e);
        }

        $connection->endSetup();

        return $this;
    }

    /**
     * @param array $orderIds
     *
     * @return $this
     */
    protected function _deleteQuoteData(array $orderIds = [])
    {
        if (!$this->_isTableExists('sales_order')) {
            return $this;
        }

        $quoteIds = $this->_getQuoteIdsByOrderIds($orderIds);

        if ($this->_isTableExists('quote_address')) {
            $selectAddressId = $this->_getSelectAddressIdByQuoteId($quoteIds);

            $this->_deleteFromTable(
                'quote_address_item',
                ['parent_item_id IN(?)' => $selectAddressId]
            );

            $this->_deleteFromTable(
                'quote_shipping_rate',
                ['address_id IN(?)' => $selectAddressId]
            );
        }

        $this->_deleteFromTable(
            'quote_id_mask',
            ['quote_id IN(?)' => $quoteIds]
        );

        if ($this->_isTableExists('quote_item')) {
            $this->_deleteFromTable(
                'quote_item_option',
                ['item_id IN(?)' => $this->_getSelectItemIdByQuoteId($quoteIds)]
            );
        }

        $this->_deleteFromTable(
            'quote', ['entity_id IN(?)' => $quoteIds]
        );

        $this->_deleteFromTable(
            'quote_address',
            ['quote_id IN(?)' => $quoteIds]
        );

        $this->_deleteFromTable(
            'quote_item',
            ['quote_id IN(?)' => $quoteIds]
        );

        $this->_deleteFromTable(
            'quote_payment',
            ['quote_id IN(?)' => $quoteIds]
        );

        return $this;
    }

    /**
     * @param      $tableName
     * @param null $schemaName
     *
     * @return bool
     */
    protected function _isTableExists($tableName, $schemaName = null)
    {
        return $this->getConnection()->isTableExists($this->getTable($tableName), $schemaName);
    }

    /**
     * @param array $orderIds
     *
     * @return array
     */
    protected function _getQuoteIdsByOrderIds(array $orderIds = [])
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('sales_order'), 'quote_id')
            ->where('entity_id IN(?)', $orderIds);

        return $connection->fetchCol($select);
    }

    /**
     * @param array $quoteIds
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getSelectAddressIdByQuoteId(array $quoteIds = [])
    {
        return $this->getConnection()->select()
            ->from($this->getTable('quote_address'), 'address_id')
            ->where('quote_id IN(?)', $quoteIds);
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed $table The table to update.
     * @param  mixed $where DELETE WHERE clause(s).
     *
     * @return int          The number of affected rows.
     */
    protected function _deleteFromTable($table, $where = '')
    {
        if ($this->_isTableExists($table)) {
            return $this->getConnection()->delete($this->getTable($table), $where);
        }

        return 0;
    }

    /**
     * @param array $quoteIds
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getSelectItemIdByQuoteId(array $quoteIds = [])
    {
        return $this->getConnection()->select()
            ->from($this->getTable('quote_item'), 'item_id')
            ->where('quote_id IN(?)', $quoteIds);
    }

    /**
     * @param $orderId
     *
     * @return $this
     */
    protected function _deleteCreditmemoData($orderIds)
    {
        if ($this->_isTableExists('sales_creditmemo')) {
            /** @var \Magento\Framework\DB\Select $selectEntityId */
            $selectEntityId = $this->getSelectEntityIdByOrderIds('sales_creditmemo', $orderIds);

            $this->_deleteFromTable(
                'sales_creditmemo_comment',
                ['parent_id IN(?)' => $selectEntityId]
            );

            $this->_deleteFromTable(
                'sales_creditmemo_item',
                ['parent_id IN(?)' => $selectEntityId]
            );
        }

        $this->_deleteFromTable(
            'sales_creditmemo',
            ['order_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_creditmemo_grid',
            ['order_id IN(?)' => $orderIds]
        );

        return $this;
    }

    /**
     * @param       $table
     * @param array $orderIds
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectEntityIdByOrderIds($table, array $orderIds = [])
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable($table), 'entity_id')
            ->where('order_id IN(?)', $orderIds);

        return $select;
    }

    /**
     * @param $orderIds
     *
     * @return $this
     */
    protected function _deleteInvoiceData($orderIds)
    {
        if ($this->_isTableExists('sales_invoice')) {
            /** @var \Magento\Framework\DB\Select $selectEntityId */
            $selectEntityId = $this->getSelectEntityIdByOrderIds('sales_invoice', $orderIds);

            $this->_deleteFromTable(
                'sales_invoice_comment',
                ['parent_id IN(?)' => $selectEntityId]
            );

            $this->_deleteFromTable(
                'sales_invoice_item',
                ['parent_id IN(?)' => $selectEntityId]
            );
        }

        $this->_deleteFromTable(
            'sales_invoice',
            ['order_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_invoice_grid',
            ['order_id IN(?)' => $orderIds]
        );

        return $this;
    }

    /**
     * @param array $orderIds
     *
     * @return $this
     */
    protected function _deleteShipmentData(array $orderIds = [])
    {
        if ($this->_isTableExists('sales_shipment')) {
            /** @var \Magento\Framework\DB\Select $selectEntityId */
            $selectEntityId = $this->getSelectEntityIdByOrderIds('sales_shipment', $orderIds);

            $this->_deleteFromTable(
                'sales_shipment_comment',
                ['parent_id IN(?)' => $selectEntityId]
            );

            $this->_deleteFromTable(
                'sales_shipment_item',
                ['parent_id IN(?)' => $selectEntityId]
            );
        }

        if ($this->_isTableExists('sales_shipment_track')) {
            $this->_deleteFromTable(
                'sales_shipment_track',
                [
                    'order_id IN(?)' => $this->_getSelectShipmentEntityId($orderIds),
                ]
            );
        }

        $this->_deleteFromTable(
            'sales_shipment',
            ['order_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_shipment_grid',
            ['order_id IN(?)' => $orderIds]
        );

        return $this;
    }

    /**
     * @param array $orderIds
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getSelectShipmentEntityId(array $orderIds = [])
    {
        return $this->getConnection()->select()
            ->from($this->getTable('sales_shipment'), 'entity_id')
            ->where('parent_id IN(?)', $orderIds);
    }

    /**
     * @param array $orderIds
     *
     * @return $this
     */
    protected function _deleteOrderData(array $orderIds = [])
    {
        $this->_deleteFromTable(
            'sales_order',
            ['entity_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_address',
            ['parent_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_item',
            ['order_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_payment',
            ['parent_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_status_history',
            ['parent_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_grid',
            ['entity_id IN(?)' => $orderIds]
        );

        $this->_deleteFromTable(
            'sales_order_tax',
            ['order_id IN(?)' => $orderIds]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('zdk_order_trash', 'ordertrash_id');
    }
}
