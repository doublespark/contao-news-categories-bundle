<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Doublespark\NewsCategoriesBundle\Models;

use Contao\Model;

/**
 * Reads and writes news categories
 */
class NewsCategoriesModel extends Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_news_categories';
}
