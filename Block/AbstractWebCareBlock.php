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
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

abstract class AbstractWebCareBlock extends Template implements BlockInterface
{
    protected $_template='DataReporter_WebCare::webcare.phtml';
    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $localeResolver;

    /**
     * AbstractWebCareBlock constructor.
     * @param \Magento\Framework\Locale\Resolver $localeResolver
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Locale\Resolver $localeResolver,
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->localeResolver = $localeResolver;
    }

    public function getWebcacheUrl($fileExtension = '.js') {
        return $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_WEBCACHEURL)
            .$this->getTypeKey()
            .$fileExtension
            .($this->shouldAppendStoreLocale()?('?lang='.$this->getCurrentLocale()):'');
    }

    public function getTypeKey() {
        return $this->escapeHtmlAttr(implode('/' , array_filter([
            $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_CLIENTID, 'store'),
            $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_ORGANISATIONID, 'store'),
            $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_WEBSITEID, 'store'),
            $this->getType()
        ])));
    }

    public function toHtml()
    {
        if ($this->_scopeConfig->getValue($this->getEnableKey())) {
            return parent::toHtml();
        }
        return '';
    }

    protected function shouldAppendStoreLocale() {
        return $this->_scopeConfig->getValue(Constants::CONFIG_WEBCARE_ADD_LANGUAGECODE)
            && $this->getCurrentLocale();
    }

    protected function getCurrentLocale() {
        $locale = $this->localeResolver->getLocale()?:$this->localeResolver->getDefaultLocale();
        if ($locale) {
            return explode('_', $locale)[0];
        }
        return null;
    }

    /**
     * Type key defining which resource should be included, see \DataReporter\WebCare\Api\Constants for possible types
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Systemconfiguration path to the yes/no option in the backend configuration for this block to be enabled/disabled
     *
     * @return string
     */
    abstract public function getEnableKey();
}