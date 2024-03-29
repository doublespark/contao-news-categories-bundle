<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['news_categories'] = [
    'tables' => ['tl_news_categories']
];

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['news']['newslist_category']        = 'Doublespark\NewsCategoriesBundle\Modules\ModuleNewsCategoryView';
$GLOBALS['FE_MOD']['news']['news_category_navigation'] = 'Doublespark\NewsCategoriesBundle\Modules\ModuleNewsCategoryNavigation';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_news_categories'] = 'Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel';