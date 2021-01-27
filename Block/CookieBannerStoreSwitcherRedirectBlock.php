<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package DataReporter\WebCare\Block
 * @copyright Copyright (c) 2021 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 26.01.2021
 */

namespace DataReporter\WebCare\Block;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class CookieBannerStoreSwitcherRedirectBlock
 * @package DataReporter\WebCare\Block
 */
class CookieBannerStoreSwitcherRedirectBlock extends Template
{
    protected $_template = 'DataReporter_WebCare::cookiebanner/store_switcher_redirect.phtml';
    protected $switcherUrlProvider;
    /**
     * @var Json
     */
    protected $jsonSerializer;
    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * CookieBannerStoreSwitcherRedirectBlock constructor.
     * @param EncoderInterface $encoder
     * @param Json $jsonSerializer
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        EncoderInterface $encoder,
        Json $jsonSerializer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->jsonSerializer = $jsonSerializer;
        $this->encoder = $encoder;
        $this->switcherUrlProvider = $this->getSwitcherUrlProvider();
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
        return str_replace('%2C', '=', $this->jsonSerializer->serialize($switchConfiguration));
    }

    /**
     * Runtime evaluation if SwitcherUrlProvider exists, as this class is not available vor magento2 v2.2.x installations.
     * If injected as a constructor argument the interceptor generation would break.
     * @return $this|mixed
     */
    protected function getSwitcherUrlProvider() {
        try {
            return ObjectManager::getInstance()->create('\Magento\Store\ViewModel\SwitcherUrlProvider');
        } catch(\ReflectionException $e) {
            return $this;
        }
    }

    /**
     * copy of function from the \Magento\Store\ViewModel\SwitcherUrlProvider class in order to be
     * able to be backwards compatible to magento2 v2.2.x where the view model does not yet exist
     * @param  $store
     * @return string
     */
    public function getTargetStoreRedirectUrl($store)
    {
        return $this->_urlBuilder->getUrl(
            'stores/store/redirect',
            [
                '___store' => $store->getCode(),
                '___from_store' => $this->_storeManager->getStore()->getCode(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->_encoder->encode(
                    $store->getCurrentUrl(false)
                ),
            ]
        );
    }
}