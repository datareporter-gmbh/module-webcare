<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 21.02.2019
 */

namespace DataReporter\WebCare\Block;


use DataReporter\Core\Api\Constants as CoreConstants;
use DataReporter\WebCare\Api\Constants;

class CookieBannerBlock extends AbstractWebCareBlock
{
    protected $_template = 'DataReporter_WebCare::cookiebanner/banner.phtml';

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
     *
     * @return bool
     */
    public function isLoaderModeEnabled(): bool
    {
        return (bool) $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_LOADER_MODE, 'store');
    }

    /**
     * Get the Loader script URL with integration key
     *
     * @return string
     */
    public function getLoaderScriptUrl(): string
    {
        $baseUrl = $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_LOADER_URL, 'store');
        $integrationKey = $this->getLoaderIntegrationKey();

        if (!$baseUrl || !$integrationKey) {
            return '';
        }

        return $this->escapeUrl($baseUrl . '?url=' . $integrationKey);
    }

    /**
     * Get the Loader Integration Key
     * Falls back to auto-generated key from client_id.organisation_id.website_id if not configured
     *
     * @return string|null
     */
    public function getLoaderIntegrationKey(): ?string
    {
        $configuredKey = $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_LOADER_INTEGRATION_KEY, 'store');
        
        if (!empty($configuredKey)) {
            return $configuredKey;
        }

        return $this->generateIntegrationKeyFromConfig();
    }

    /**
     * Generate Integration Key from existing DataReporter configuration
     * Format: {client_id}.{organisation_id}.{website_id}
     *
     * @return string|null
     */
    protected function generateIntegrationKeyFromConfig(): ?string
    {
        $clientId = $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_CLIENTID, 'store');
        $organisationId = $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_ORGANISATIONID, 'store');
        $websiteId = $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_WEBSITEID, 'store');

        if (empty($clientId) || empty($organisationId) || empty($websiteId)) {
            return null;
        }

        return $clientId . '.' . $organisationId . '.' . $websiteId;
    }

    /**
     * Get the SRI hash for the Loader script
     *
     * @return string|null
     */
    public function getLoaderSriHash(): ?string
    {
        return $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_LOADER_SRI_HASH, 'store');
    }

    /**
     * Check if SRI is enabled (hash is configured)
     *
     * @return bool
     */
    public function isSriEnabled(): bool
    {
        $hash = $this->getLoaderSriHash();
        return !empty($hash);
    }
}