<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Doublespark\NewsCategoriesBundle\Modules;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\Module;
use Contao\PageModel;
use Contao\System;
use Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel;

/**
 * Front end module "category navigation".
 */
class ModuleNewsCategoryNavigation extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_news_category_navigation';

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if(System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest()))
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### NEWS CATEGORY NAVIGATION ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        if($this->jumpTo)
        {
            $objJumpToPage = PageModel::findByPk($this->jumpTo);
            $jumpToAlias = $objJumpToPage->alias;
        }
        else
        {
            $jumpToAlias = '';
        }

        $objCategories = NewsCategoriesModel::findAll(['orderBy' => 'title ASC']);

        $arrCategories = array();

        if($objCategories)
        {
            $currentUrl = Environment::get('requestUri');

            while($objCategories->next())
            {
                $arrCategory = $objCategories->row();

                $arrCategory['href'] = '/'.$jumpToAlias.'/'.$arrCategory['alias'];

                if($currentUrl == $arrCategory['href'])
                {
                    $arrCategory['class'] = 'active';
                }
                else
                {
                    $arrCategory['class'] = '';
                }

                $arrCategories[] = $arrCategory;
            }
        }

        $this->Template->arrCategories = $arrCategories;
    }
}