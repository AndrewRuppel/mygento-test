<?php

namespace AndrewRuppel\FeedImport\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Xml\Parser;
use AndrewRuppel\FeedImport\Api\ImportInterface;

class YmlImport implements ImportInterface
{
    /**
     * @var Parser
     */
    protected $xmlParcer;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor.
     *
     * @param Parser $xmlParcer
     * @param ProductRepositoryInterface $productRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Parser $xmlParcer,
        ProductRepositoryInterface $productRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->xmlParcer = $xmlParcer;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function beginImport()
    {
        $YmlFeedUrl = $this->scopeConfig->getValue(
            'settings/import/import_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $this->xmlParcer->load($YmlFeedUrl);
        $offers = $this->xmlParcer->getDom()->getElementsByTagName("offer");

        if ($offers->count() > 0) {
            /**
             * @var \DOMElement $offer
             */
            foreach ($offers as $offer) {
                $isAvailable = $offer->getAttribute('available') ?: false;

                if ($isAvailable) {
                    $id  = $offer->getAttribute('id');
                    $url = $offer->getElementsByTagName('url')->item(0)->nodeValue;

                    try {
                        /**
                         * @var ProductInterface $product
                         */
                        $product = $this->productRepository->get($id);
                        $product->setCustomAttribute('product_url', $url);
                        $this->productRepository->save($product);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
    }
}
