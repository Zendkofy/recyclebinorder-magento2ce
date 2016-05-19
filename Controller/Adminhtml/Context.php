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

class Context extends \Magento\Backend\App\Action\Context
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

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
     * Context constructor.
     *
     * @param \Magento\Framework\App\RequestInterface                    $request
     * @param \Magento\Framework\App\ResponseInterface                   $response
     * @param \Magento\Framework\ObjectManagerInterface                  $objectManager
     * @param \Magento\Framework\Event\ManagerInterface                  $eventManager
     * @param \Magento\Framework\UrlInterface                            $url
     * @param \Magento\Framework\App\Response\RedirectInterface          $redirect
     * @param \Magento\Framework\App\ActionFlag                          $actionFlag
     * @param \Magento\Framework\App\ViewInterface                       $view
     * @param \Magento\Framework\Message\ManagerInterface                $messageManager
     * @param \Magento\Backend\Model\View\Result\RedirectFactory         $resultRedirectFactory
     * @param \Magento\Framework\Controller\ResultFactory                $resultFactory
     * @param \Magento\Backend\Model\Session                             $session
     * @param \Magento\Framework\AuthorizationInterface                  $authorization
     * @param \Magento\Backend\Model\Auth                                $auth
     * @param \Magento\Backend\Helper\Data                               $helper
     * @param \Magento\Backend\Model\UrlInterface                        $backendUrl
     * @param \Magento\Framework\Data\Form\FormKey\Validator             $formKeyValidator
     * @param \Magento\Framework\Locale\ResolverInterface                $localeResolver
     * @param OrderTrashManagementInterface                              $orderTrashManagement
     * @param \Magento\Ui\Component\MassAction\Filter                    $massActionfilter
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Model\OrderFactory                          $orderFactory
     * @param bool                                                       $canUseBaseUrl
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\App\ViewInterface $view,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Backend\Model\Session $session,
        \Magento\Framework\AuthorizationInterface $authorization,
        \Magento\Backend\Model\Auth $auth,
        \Magento\Backend\Helper\Data $helper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        OrderTrashManagementInterface $orderTrashManagement,
        \Magento\Ui\Component\MassAction\Filter $massActionfilter,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        $canUseBaseUrl = false
    ) {
        parent::__construct(
            $request, 
            $response, 
            $objectManager, 
            $eventManager,
            $url, 
            $redirect,
            $actionFlag,
            $view,
            $messageManager,
            $resultRedirectFactory,
            $resultFactory, 
            $session,
            $authorization,
            $auth,
            $helper,
            $backendUrl, 
            $formKeyValidator,
            $localeResolver,
            $canUseBaseUrl = false
        );

        $this->_orderTrashManagement = $orderTrashManagement;
        $this->_massActionfilter = $massActionfilter;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderFactory = $orderFactory;
    }

    /**
     * @return OrderTrashManagementInterface
     */
    public function getOrderTrashManagement()
    {
        return $this->_orderTrashManagement;
    }

    /**
     * @return \Magento\Ui\Component\MassAction\Filter
     */
    public function getMassActionfilter()
    {
        return $this->_massActionfilter;
    }

    /**
     * @return \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    public function getOrderCollectionFactory()
    {
        return $this->_orderCollectionFactory;
    }

    /**
     * @return \Magento\Sales\Model\OrderFactory
     */
    public function getOrderFactory()
    {
        return $this->_orderFactory;
    }
}