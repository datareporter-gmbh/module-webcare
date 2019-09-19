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

class ImprintBlock extends AbstractWebCareBlock
{

    public function getTypeKey() {
        return $this->escapeHtmlAttr(implode('/' , array_filter([
            $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_CLIENTID, 'store'),
            $this->_scopeConfig->getValue(CoreConstants::CONFIG_GENERAL_ORGANISATIONID, 'store'),
            $this->getType()
        ])));
    }

    public function getType()
    {
        return Constants::TYPE_IMPRINT;
    }

    public function getEnableKey()
    {
        return Constants::CONFIG_WEBCARE_ENABLE_IMPRINT;
    }
}