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

namespace Zendkofy\RecycleBinOrder\Controller\Adminhtml\Trash;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class MassRestore extends \Zendkofy\RecycleBinOrder\Controller\Adminhtml\AbstractMassAction
{

    /**
     * @param AbstractCollection $collection
     *
     * @return ResponseInterface|ResultInterface
     */
    protected function _massAction(AbstractCollection $collection)
    {
        $this->_orderTrashManagement->restoreOrders($collection);

        $this->messageManager->addSuccess(
            __('You have restored %1 order(s).',
                $collection->getSize())
        );

        return $this->_getRedirectResult();
    }

    /**
     * @return AbstractCollection
     */
    public function initCollection()
    {
        return $this->_orderCollectionFactory->create();
    }
}