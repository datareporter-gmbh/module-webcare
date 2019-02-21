<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 21.02.2019
 */

namespace DataReporter\WebCare\Block;


use DataReporter\WebCare\Api\Constants;

class PrivacyNoticeBlock extends AbstractWebCareBlock
{

    public function getType()
    {
        return Constants::TYPE_PRIVACYNOTICE;
    }

    public function getEnableKey()
    {
        return Constants::CONFIG_WEBCARE_ENABLE_PRIVACYNOTICE;
    }
}