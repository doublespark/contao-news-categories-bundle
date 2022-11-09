<?php

/***
 * DoublesparkNewsCategoriesBundle
 */
namespace Doublespark\NewsCategoriesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DoublesparkNewsCategoriesBundle
 *
 * @package DoublesparkNewsCategoriesBundle
 */
class DoublesparkNewsCategoriesBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}