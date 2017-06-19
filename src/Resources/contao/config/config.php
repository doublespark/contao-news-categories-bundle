<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 2, array
(
    'news_categories' => array
    (
        'tables' => array('tl_news_categories')
    )
));

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['news']['newslist_category']        = 'Doublespark\NewsCategoriesBundle\Modules\ModuleNewsCategoryView';
$GLOBALS['FE_MOD']['news']['news_category_navigation'] = 'Doublespark\NewsCategoriesBundle\Modules\ModuleNewsCategoryNavigation';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_news_categories'] = 'Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel';

/**
 * Hooks
 */
//$GLOBALS['TL_HOOKS']['generatePage'][] = array('Doublespark\Hooks\HookGeneratePage','addCanonicalTag');

/**
 * Content elements
 */
//$GLOBALS['TL_CTE']['links']['boxlink'] = 'Doublespark\Elements\ContentBoxLink';
//$GLOBALS['TL_CTE']['media']['parallax_section'] = 'Doublespark\Elements\ParallaxSectionElement';
//$GLOBALS['TL_CTE']['texts']['double_text'] = 'Doublespark\Elements\DoubleTextElement';