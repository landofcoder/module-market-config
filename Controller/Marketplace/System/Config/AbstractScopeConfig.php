<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Lof\MarketConfig\Controller\Marketplace\System\Config;

use Lof\MarketConfig\Controller\Marketplace\System\ConfigSectionChecker;

/**
 * @api
 * @since 100.0.2
 */
abstract class AbstractScopeConfig extends \Lof\MarketConfig\Controller\Marketplace\System\AbstractConfig
{
    /**
     * @var \Magento\Config\Model\Config
     */
    protected $_backendConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param ConfigSectionChecker $sectionChecker
     * @param \Magento\Config\Model\Config $backendConfig
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Config\Model\Config\Structure $configStructure,
        ConfigSectionChecker $sectionChecker,
        \Magento\Config\Model\Config $backendConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl
    ) {
        $this->_backendConfig = $backendConfig;
        parent::__construct($context, $configStructure, $customerSession, $customerUrl, $sectionChecker);
    }

    /**
     * Sets scope for backend config
     *
     * @param string $sectionId
     * @return bool
     */
    protected function isSectionAllowed($sectionId)
    {
        $website = $this->getRequest()->getParam('website');
        $store = $this->getRequest()->getParam('store');
        if ($store) {
            $this->_backendConfig->setStore($store);
        } elseif ($website) {
            $this->_backendConfig->setWebsite($website);
        }
        return parent::isSectionAllowed($sectionId);
    }
}
