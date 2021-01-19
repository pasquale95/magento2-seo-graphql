<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Console;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Framework\Exception\LocalizedException;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Hreflang
 * CLI command to run in order to generate all the hreflang attributes for products,
 * categories and CMS pages.
 *
 * @package Paskel\Seo\Console
 */
class Hreflang extends Command
{
    /** Constants to define the CLI command full name */
    const FAMILY = "hreflang";
    const COMMAND = "generate";

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var PageCollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @var HreflangHelper
     */
    protected $hreflangHelper;

    /**
     * Hreflang constructor.
     *
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param HreflangHelper $hreflangHelper
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        PageCollectionFactory $pageCollectionFactory,
        HreflangHelper $hreflangHelper
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->hreflangHelper = $hreflangHelper;
        parent::__construct(self::FAMILY . ':' . self::COMMAND);
    }

    /**
     * Setup the command config
     */
    protected function configure() {
        $this->setName(self::FAMILY . ':' . self::COMMAND)
            ->setDescription('Generates the hreflang attribute for all products,
            categories and CMS pages.');
        parent::configure();
    }

    /**
     * Execute the hreflang generation
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this|int
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        // generate for categories
        $this->generateCategoriesHreflang();
        $output->writeln("Hreflang generated for categories.");

        // generate for products
        $this->generateProductsHreflang();
        $output->writeln("Hreflang generated for products.");

        // generate for cms pages
        $this->generateCmsPagesHreflang();
        $output->writeln("Hreflang generated for cms pages.");

        return $this;
    }

    /**
     * Generates all Hreflang at once.
     *
     * @throws LocalizedException
     */
    public function generateHreflangs() {
        // generate for categories
        $this->generateCategoriesHreflang();
        // generate for products
        $this->generateProductsHreflang();
        // generate for cms pages
        $this->generateCmsPagesHreflang();
    }

    /**
     * Generate the hreflang attributes for all the categories
     *
     * @throws LocalizedException
     */
    protected function generateCategoriesHreflang() {
        $categories = $this->categoryCollectionFactory->create()
            ->getItems();
        foreach ($categories as $category) {
            $this->hreflangHelper->generateHreflang(
                $category->getId(),
                CategoryUrlRewriteGenerator::ENTITY_TYPE,
                $category->getStoreIds()
            );
        }
    }

    /**
     * Generate the hreflang attributes for all the products
     *
     * @throws LocalizedException
     */
    protected function generateProductsHreflang() {
        $products = $this->productCollectionFactory->create()
            ->getItems();
        foreach ($products as $product) {
            $categoryIds = $product->getCategoryIds();
            $this->hreflangHelper->generateHreflang(
                $product->getId(),
                ProductUrlRewriteGenerator::ENTITY_TYPE,
                $product->getStoreIds(),
                null,
                $categoryIds ? max($categoryIds) : null
            );
        }
    }

    /**
     * Generate the hreflang attributes for all the cms pages
     *
     * @throws LocalizedException
     */
    protected function generateCmsPagesHreflang() {
        $pages = $this->pageCollectionFactory->create()
            ->getItems();
        foreach ($pages as $page) {
            $this->hreflangHelper->generateHreflang(
                $page->getId(),
                CmsPageUrlRewriteGenerator::ENTITY_TYPE,
                $page->getStores(),
                $page->getIdentifier()
            );
        }
    }
}
