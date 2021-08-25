<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MarketConfig
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\MarketConfig\Controller\Marketplace\System;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;

/**
 * System Configuration Abstract Controller
 * phpcs:disable Magento2.Classes.AbstractApi
 * @api
 * @since 100.0.2
 *
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
abstract class AbstractConfig extends \Magento\Framework\App\Action\Action
{
    const FLAG_IS_URLS_CHECKED = 'check_url_settings';
    /**
     * @var \Lof\MarketConfig\Model\Config\Structure
     */
    protected $_configStructure;

    /**
     * @deprecated 101.0.0
     */
    protected $_sectionChecker;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Lof\MarketConfig\Model\Config\Structure $configStructure
     * @param mixed $sectionChecker - deprecated
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Lof\MarketConfig\Model\Config\Structure $configStructure,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        $sectionChecker
    ) {
        parent::__construct($context);
        $this->_configStructure = $configStructure;
        $this->_sectionChecker = $sectionChecker;
        $this->_customerSession = $customerSession;
        $this->_customerUrl = $_customerUrl;
    }

    /**
     * Retrieve customer session object.
     *
     * @return \Magento\Customer\Model\Session
     */
    protected function _getSession()
    {
        return $this->_customerSession;
    }

    /**
     * Check customer authentication.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->_customerUrl->getLoginUrl();
        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }


    /**
     * Save state of configuration field sets
     *
     * @param array $configState
     * @return bool
     */
    protected function _saveState($configState = [])
    {
        if (is_array($configState)) {
            $configState = $this->sanitizeConfigState($configState);
            $adminUser = $this->_auth->getUser();
            $extra = $adminUser->getExtra();
            if (!is_array($extra)) {
                $extra = [];
            }
            if (!isset($extra['configState'])) {
                $extra['configState'] = [];
            }
            foreach ($configState as $fieldset => $state) {
                $extra['configState'][$fieldset] = $state;
            }
            $adminUser->saveExtra($extra);
        }
        return true;
    }

    /**
     * Sanitize config state data
     *
     * @param array $configState
     * @return array
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @since 100.1.0
     */
    protected function sanitizeConfigState($configState)
    {
        $sectionList = $this->_configStructure->getSectionList();
        $sanitizedConfigState = $configState;
        foreach ($configState as $sectionId => $value) {
            if (array_key_exists($sectionId, $sectionList)) {
                $sanitizedConfigState[$sectionId] = (bool)$sanitizedConfigState[$sectionId] ? '1' : '0';
            } else {
                unset($sanitizedConfigState[$sectionId]);
            }
        }
        return $sanitizedConfigState;
    }
}
