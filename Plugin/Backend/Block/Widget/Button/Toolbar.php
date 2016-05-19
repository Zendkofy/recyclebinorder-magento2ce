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

namespace Zendkofy\RecycleBinOrder\Plugin\Backend\Block\Widget\Button;

use Zendkofy\RecycleBinOrder\Api\OrderTrashManagementInterface;
use Zendkofy\RecycleBinOrder\Helper\Config\ConfigManagement as OrderTrashConfigManagement;

class Toolbar
{
    /**
     * @var array
     */
    protected $_oldButtonListId = [
        'back',
        'order_edit',
        'order_cancel',
        'send_notification',
        'order_creditmemo',
        'void_payment',
        'order_hold',
        'order_unhold',
        'accept_payment',
        'deny_payment',
        'get_review_payment_update',
        'order_invoice',
        'order_ship',
        'order_reorder',
    ];

    /**
     * @var OrderTrashManagementInterface
     */
    protected $_orderTrashManagement;

    /**
     * @var OrderTrashConfigManagement
     */
    protected $_orderTrashConfigManagement;

    /**
     * Toolbar constructor.
     *
     * @param OrderTrashManagementInterface $orderTrashManagement
     * @param OrderTrashConfigManagement    $orderTrashConfigManagement
     * @param array                         $oldButtonListId
     */
    public function __construct(
        OrderTrashManagementInterface $orderTrashManagement,
        OrderTrashConfigManagement $orderTrashConfigManagement,
        $oldButtonListId = []
    ) {
        $this->_orderTrashManagement = $orderTrashManagement;
        $this->_orderTrashConfigManagement = $orderTrashConfigManagement;

        if (!empty($oldButtonListId)) {
            $this->_oldButtonListId = array_merge($this->_oldButtonListId, $oldButtonListId);
        }
    }

    /**
     * @param \Magento\Backend\Block\Widget\Button\Toolbar    $subject
     * @param \Closure                                        $proceed
     * @param \Magento\Framework\View\Element\AbstractBlock   $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
     */
    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        if ($context instanceof \Magento\Sales\Block\Adminhtml\Order\View
            && $context->getOrderId()
            && $this->_orderTrashConfigManagement->isEnabled()
        ) {
            if ($this->_orderTrashManagement->isOrderInTrash($context->getOrderId())) {
                $this->_clearOldButton($context);

                $this->_addGotoOrderManageButton($context);
                $this->_addGotoTrashManageButton($context);
                $this->_addDeleteButton($context);
                $this->_addRestoreOrderButton($context);
            } else {
                $this->_addDeleteButton($context);
                $this->_addMoveToTrashButton($context);
            }
        }

        return [$context, $buttonList];
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return $this
     */
    protected function _clearOldButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        foreach ($this->_oldButtonListId as $buttonId) {
            $context->removeButton($buttonId);
        }

        return $this;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return $this
     */
    protected function _addDeleteButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        $context->addButton(
            'delete_order',
            [
                'label'          => __('Delete'),
                'class'          => 'delete_order',
                'data_attribute' => [
                    'mage-init' => [
                        'clickOpenWindow' => [
                            'URL'          => $this->_getDeleteOrderUrl($context),
                            'name'         => '_self',
                            'allowConfirm' => true,
                            'confirm'      => [
                                'title'   => __('Delete items'),
                                'message' => __(
                                    'Are you sure you want to delete order %1 ?',
                                    $context->getOrder()->getIncrementId()
                                ),
                            ],
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return $this
     */
    protected function _addMoveToTrashButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        $context->addButton(
            'move_to_trash',
            [
                'label'          => __('Move To Trash'),
                'class'          => 'move_to_trash',
                'data_attribute' => [
                    'mage-init' => [
                        'clickOpenWindow' => [
                            'URL'  => $this->_getMoveToTrashUrl($context),
                            'name' => '_self',
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return $this
     */
    protected function _addRestoreOrderButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        $context->addButton(
            'restore_order',
            [
                'label'          => __('Restore Order'),
                'class'          => 'restore_order',
                'data_attribute' => [
                    'mage-init' => [
                        'clickOpenWindow' => [
                            'URL'  => $this->_getRestoreOrderUrl($context),
                            'name' => '_self',
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    protected function _addGotoTrashManageButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        $context->addButton(
            'goto_trash',
            [
                'label'          => __('Go To Recycle Bin'),
                'class'          => 'goto_trash',
                'data_attribute' => [
                    'mage-init' => [
                        'clickOpenWindow' => [
                            'URL'  => $context->getUrl('recyclebinadmin/trash'),
                            'name' => '_self',
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return $this
     */
    protected function _addGotoOrderManageButton(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        $context->addButton(
            'goto_order_manage',
            [
                'label'          => __('Go To Order Manage'),
                'class'          => 'goto_order_manage',
                'data_attribute' => [
                    'mage-init' => [
                        'clickOpenWindow' => [
                            'URL'  => $context->getUrl('sales/order/index'),
                            'name' => '_self',
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return string
     */
    protected function _getRestoreOrderUrl(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        return $context->getUrl(
            'recyclebinadmin/order/restore',
            ['order_id' => $context->getOrderId()]
        );
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return string
     */
    public function _getDeleteOrderUrl(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        return $context->getUrl(
            'recyclebinadmin/order/delete',
            ['order_id' => $context->getOrderId()]
        );
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $context
     *
     * @return string
     */
    public function _getMoveToTrashUrl(\Magento\Sales\Block\Adminhtml\Order\View $context)
    {
        return $context->getUrl(
            'recyclebinadmin/order/moveToTrash',
            ['order_id' => $context->getOrderId()]
        );
    }
}