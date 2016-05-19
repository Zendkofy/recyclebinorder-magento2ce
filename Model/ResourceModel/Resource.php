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

class Resource
{
    protected $_resourceConnection;

    /**
     * Resource constructor.
     *
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->_resourceConnection = $resourceConnection;
    }

    /**
     * insert data to table.
     *
     * @param       $table
     * @param array $data
     *
     * @throws LocalizedException
     */
    public function insertData($table, array $columns, array $data = [])
    {
        if (empty($data)) {
            return;
        }

        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->insertArray($table, $columns, $data);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->_resourceConnection->getConnection();
    }

    /**
     * delete data from table.
     *
     * @param       $table
     * @param array $where
     *
     * @throws LocalizedException
     */
    public function deleteData($table, array $where = [])
    {
        if (empty($where)) {
            return;
        }

        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->delete($table, $where);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * update data for table.
     *
     * @param $table
     * @param $bind
     * @param $where
     *
     * @throws LocalizedException
     */
    public function updateData($table, $bind, $where)
    {
        if (empty($where)) {
            return;
        }

        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->update($table, $bind, $where);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}