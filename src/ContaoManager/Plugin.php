<?php

declare(strict_types=1);

namespace Doublespark\NewsCategoriesBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use Doublespark\NewsCategoriesBundle\DoublesparkNewsCategoriesBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [BundleConfig::create(DoublesparkNewsCategoriesBundle::class)->setLoadAfter([ContaoNewsBundle::class])];
    }
}
