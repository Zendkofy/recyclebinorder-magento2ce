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
use Magento\Framework\Controller\ResultFactory;

class Delete extends \Zendkofy\RecycleBinOrder\Controller\Adminhtml\Trash
{

    /**
     * @var string
     */
    protected $redirectUrl = 'sales/*/';

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $order = $this->_orderFactory->create()->load($orderId);
            if (!$order->getId()) {
                $this->messageManager->addSuccess(
                    __('Order no longer exists.')
                );

                return $resultRedirect->setPath($this->redirectUrl);
            }

            $this->_orderTrashManagement->deleteOrders($orderId);
            $this->messageManager->addSuccess(
                __('You have deleted order %1.', $order->getIncrementId())
            );

            return $resultRedirect->setPath($this->redirectUrl);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }
}