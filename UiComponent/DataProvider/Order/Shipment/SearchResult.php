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

namespace Zendkofy\RecycleBinOrder\UiComponent\DataProvider\Order\Shipment;

class SearchResult extends \Zendkofy\RecycleBinOrder\UiComponent\DataProvider\SearchResult
{
    protected function _renderFiltersBefore()
    {
        if($this->_orderTrashConfigManagement->isEnabled()) {
            $this->_orderTrashManagement->filterShipmentGridCollection($this);
        }
        parent::_renderFiltersBefore();
    }
}