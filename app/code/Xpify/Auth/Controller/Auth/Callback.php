<?php
declare(strict_types=1);

namespace Xpify\Auth\Controller\Auth;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\Controller\Result\RedirectFactory;
use Shopify\Auth\OAuth;
use Shopify\Utils;
use Shopify\Webhooks\Topics;
use Xpify\App\Service\GetCurrentApp;
use Xpify\Core\Helper\ShopifyContextInitializer;
use Xpify\Webhook\Service\Webhook;
use Xpify\App\Service\EnsureBilling;
use Xpify\App\Api\Data\AppInterface as IApp;

class Callback implements HttpGetActionInterface
{
    private IRequest $request;
    private Webhook $webhookManager;
    private RedirectFactory $resultRedirectFactory;
    private GetCurrentApp $getCurrentApp;
    private ShopifyContextInitializer $contextInitializer;

    /**
     * @param IRequest $request
     * @param Webhook $webhookManager
     * @param RedirectFactory $resultRedirectFactory
     * @param ShopifyContextInitializer $contextInitializer
     * @param GetCurrentApp $getCurrentApp
     */
    public function __construct(
        IRequest $request,
        Webhook $webhookManager,
        RedirectFactory $resultRedirectFactory,
        ShopifyContextInitializer $contextInitializer,
        GetCurrentApp $getCurrentApp
    ) {
        $this->request = $request;
        $this->webhookManager = $webhookManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->getCurrentApp = $getCurrentApp;
        $this->contextInitializer = $contextInitializer;
    }
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $app = $this->getCurrentApp->get();
        $this->contextInitializer->initialize($app);
        $session = OAuth::callback(
            $_COOKIE,
            $this->getRequest()->getQuery()->toArray(),
            ['Xpify\Auth\Service\CookieHandler', 'saveShopifyCookie'],
        );
        $host = $this->getRequest()->getParam('host');
        $shop = Utils::sanitizeShopDomain($this->getRequest()->getParam('shop'));
        $this->webhookManager->register(Topics::APP_UNINSTALLED, $shop, $session->getAccessToken(), $app);
        $redirectUrl = Utils::getEmbeddedAppUrl($host);
        if ($app->isBillingRequired()) {
            list($hasPayment, $confirmationUrl) = EnsureBilling::check($session, []);

            if (!$hasPayment) {
                $redirectUrl = $confirmationUrl;
            }
        }

        return $this->resultRedirectFactory->create()->setUrl($redirectUrl);
    }

    /**
     * @return IRequest
     */
    public function getRequest(): IRequest
    {
        return $this->request;
    }
}