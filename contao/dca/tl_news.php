<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * Add palettes to tl_news
 */
PaletteManipulator::create()
    ->addLegend('category_legend','date_legend',PaletteManipulator::POSITION_AFTER)
    ->addField('categories','category_legend', PaletteManipulator::POSITION_PREPEND)
    ->applyToPalette('default', 'tl_news');

/**
 * Add fields to tl_news
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['categories'] = [
    'exclude'					=> true,
    'inputType'					=> 'checkbox',
    'foreignKey'			    => 'tl_news_categories.title',
    'eval'						=> ['mandatory'=>true, 'multiple' => true],
    'sql'                       => "blob NULL"
];