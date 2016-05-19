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
use Magento\Framework\Controller\ResultFactory;

class Index extends \Zendkofy\RecycleBinOrder\Controller\Adminhtml\Trash
{
    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Zendkofy_RecycleBinOrder::order');
        $resultPage->addBreadcrumb(__('Sales'), __('Sales'));
        $resultPage->addBreadcrumb(__('Recycle Bin'), __('Recycle Bin'));
        $resultPage->getConfig()->getTitle()->prepend(__('Orders'));

        return $resultPage;
    }
}