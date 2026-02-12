<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2020 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 28.04.2020
 */

namespace DataReporter\WebCare\Block;

use DataReporter\WebCare\Api\Constants;
use Magento\Framework\View\Element\Template;

/**
 * Class CookieBannerOptionsBlock
 * @package DataReporter\WebCare\Block
 */
class CookieBannerOptionsBlock extends Template
{
    /**
     * @return bool
     */
    public function isCustomOptionsEnabled()
    {
        return $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_ENABLE_COOKIEBANNER_OPTIONS, 'store') == true;
    }

    public function isCustomRedirectAfterConsentEnabled()
    {
        return $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_ENABLE_COOKIEBANNER_CUSTOMREDIRECT, 'store') == true;
    }

    /**
     * Check if Loader Mode (CSP/SRI compliant) is enabled
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
