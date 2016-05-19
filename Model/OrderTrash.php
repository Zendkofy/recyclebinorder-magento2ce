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

/**
 * Class OrderTrash
 * @method \Zendkofy\RecycleBinOrder\Model\ResourceModel\OrderTrash getResource()
 * @method \Zendkofy\RecycleBinOrder\Model\ResourceModel\OrderTrash _getResource()
 * @package Zendkofy\RecycleBinOrder\Model
 */
class OrderTrash extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zendkofy\RecycleBinOrder\Model\ResourceModel\OrderTrash');
    }
}
