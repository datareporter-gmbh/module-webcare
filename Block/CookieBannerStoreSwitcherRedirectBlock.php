<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2021 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 26.01.2021
 */

namespace DataReporter\WebCare\Block;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;

/**
 * Class CookieBannerStoreSwitcherRedirectBlock
 * @package DataReporter\WebCare\Block
 */
class CookieBannerStoreSwitcherRedirectBlock extends Template
{
    protected $_template = 'DataReporter_WebCare::cookiebanner/store_switcher_redirect.phtml';
    /**
     * @var \Magento\Store\ViewModel\SwitcherUrlProvider
     */
    private $switcherUrlProvider;
    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * CookieBannerStoreSwitcherRedirectBlock constructor.
     * @param \Magento\Store\ViewModel\SwitcherUrlProvider $switcherUrlProvider
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Store\ViewModel\SwitcherUrlProvider $switcherUrlProvider,
        Json $jsonSerializer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->switcherUrlProvider = $switcherUrlProvider;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function getSwitchUrlConfiguration()
    {
        $currentStore = $this->_storeManager->getStore()->getCode();
        $switchConfiguration = [];
        foreach ($this->_storeManager->getStores(false, true) as $storeCode => $store) {
            if ($storeCode == $currentStore) {
                // skip current store as no redirect is needed
                continue;
            }
            $switchConfiguration[$storeCode] = $this->switcherUrlProvider->getTargetStoreRedirectUrl($store);
        }
        return $this->jsonSerializer->serialize($switchConfiguration);
    }
}