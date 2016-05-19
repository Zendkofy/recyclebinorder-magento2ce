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

namespace Zendkofy\RecycleBinOrder\Controller\Adminhtml\Order;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class MassMoveToTrash extends \Zendkofy\RecycleBinOrder\Controller\Adminhtml\AbstractMassAction
{
    /**
     * @param AbstractCollection $collection
     *
     * @return ResponseInterface|ResultInterface
     */
    protected function _massAction(AbstractCollection $collection)
    {
        $this->_orderTrashManagement->moveOrdersToTrash($collection);
        $this->messageManager->addSuccess(
            __('You have move %1 order(s) to trash.',
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