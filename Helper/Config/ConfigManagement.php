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

namespace Zendkofy\RecycleBinOrder\Helper\Config;

class ConfigManagement extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MODULE_NAME = 'Zendkofy_RecycleBinOrder';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_moduleManager->isOutputEnabled(self::MODULE_NAME);
    }
}