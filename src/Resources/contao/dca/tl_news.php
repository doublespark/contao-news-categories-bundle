<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] =  str_replace
(
    '{date_legend}',
    '{category_legend},categories;{date_legend}',
    $GLOBALS['TL_DCA']['tl_news']['palettes']['default']
);

/**
 * Callbacks
 */
$GLOBALS['TL_DCA']['tl_news']['config']['onsubmit_callback'][] = array('tl_news_nc','saveNewsCategoryRelationships');

/**
 * Add fields to tl_news
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['categories'] = array
(
    'label'						=> &$GLOBALS['TL_LANG']['tl_news']['categories'],
    'exclude'					=> true,
    'inputType'					=> 'checkbox',
    'foreignKey'			    => 'tl_news_categories.title',
    'eval'						=> array('mandatory'=>true, 'multiple' => true),
    'sql'                       => "blob NULL"
);


/**
 * Class tl_news_nc
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_news_nc extends Backend
{
    /**
     * Save the relationships
     * @param DataContainer $dc
     */
    public function saveNewsCategoryRelationships(DataContainer $dc)
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord)
        {
            return;
        }

        // Clear previous relations
        $this->Database->prepare('DELETE FROM tl_news_category WHERE news_id=?')->execute($dc->id);

        $arrCategories = array();

        // Check if we have an serialized value
        if(!is_array($dc->activeRecord->categories) AND !empty($dc->activeRecord->categories))
        {
            $arrCategories = unserialize($dc->activeRecord->categories);
        }
        if(is_array($dc->activeRecord->categories))
        {
            $arrCategories = $dc->activeRecord->categories;
        }

        $values = array();
        $placeholders = array();

        // Build placeholders and values array
        foreach($arrCategories as $category_id)
        {
            $placeholders[] = '(?,?)';

            $values[] = $dc->id;
            $values[] = $category_id;
        }

        // Save categories
        if(count($placeholders) > 0)
        {
            $this->Database->prepare("INSERT INTO tl_news_category (news_id, category_id) VALUES ".implode(',',$placeholders))->execute($values);
        }
    }
}