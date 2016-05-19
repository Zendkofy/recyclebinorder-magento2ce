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

namespace Zendkofy\RecycleBinOrder\Controller\Adminhtml;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

abstract class AbstractMassAction extends \Zendkofy\RecycleBinOrder\Controller\Adminhtml\Trash
{
    /**
     * @var string
     */
    protected $redirectUrl = '*/*/';

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        try {
            $collection = $this->_massActionfilter->getCollection($this->initCollection());

            return $this->_massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * Return component referer url
     *
     * @return null|string
     */
    protected function _getRedirectUrl()
    {
        return $this->_massActionfilter->getComponentRefererUrl()
            ? $this->_massActionfilter->getComponentRefererUrl() : 'sales/*/';
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _getRedirectResult()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->_getRedirectUrl());

        return $resultRedirect;
    }

    /**
     * Set status to collection items
     *
     * @param AbstractCollection $collection
     *
     * @return ResponseInterface|ResultInterface
     */
    abstract protected function _massAction(AbstractCollection $collection);

    /**
     * @return AbstractCollection
     */
    abstract public function initCollection();
}