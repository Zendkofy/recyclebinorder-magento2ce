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

use Zendkofy\RecycleBinOrder\Api\OrderTrashManagementInterface;

abstract class Trash extends \Magento\Backend\App\Action
{
    /**
     * @var OrderTrashManagementInterface
     */
    protected $_orderTrashManagement;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_massActionfilter;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * Trash constructor.
     *
     * @param Context $context
     */
    public function __construct(
        \Zendkofy\RecycleBinOrder\Controller\Adminhtml\Context $context
    ) {
        parent::__construct($context);
        $this->_orderTrashManagement = $context->getOrderTrashManagement();
        $this->_massActionfilter = $context->getMassActionfilter();
        $this->_orderCollectionFactory = $context->getOrderCollectionFactory();
        $this->_orderFactory = $context->getOrderFactory();
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('Zendkofy_RecycleBinOrder::order');
    }
}