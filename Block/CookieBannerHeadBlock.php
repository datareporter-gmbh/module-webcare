<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 21.02.2019
 */

namespace DataReporter\WebCare\Block;


use DataReporter\WebCare\Api\Constants;

class CookieBannerHeadBlock extends AbstractWebCareBlock
{
    protected $_template = 'DataReporter_WebCare::cookiebanner/head.phtml';

    public function getType()
    {
        return Constants::TYPE_COOKIEBANNER;
    }

    public function getEnableKey()
    {
        return Constants::CONFIG_WEBCARE_ENABLE_COOKIEBANNER;
    }

    public function hasNoScriptVersion()
    {
        return false;
    }

    /**
     * Check if Loader Mode (CSP/SRI compliant) is enabled
     * In Loader Mode, no separate CSS is needed as the Loader handles everything
     *
     * @return bool
     */
    public function isLoaderModeEnabled(): bool
    {
        return (bool) $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_LOADER_MODE, 'store');
    }

    public function toHtml()
    {
        if ($this->isLoaderModeEnabled()) {
            return '';
        }
        return parent::toHtml();
    }
}