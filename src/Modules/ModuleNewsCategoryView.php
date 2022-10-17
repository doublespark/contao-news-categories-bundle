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
use Contao\Config;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Environment;
use Contao\Input;
use Contao\ModuleNewsList;
use Contao\Pagination;
use Contao\StringUtil;
use Doublespark\NewsCategoriesBundle\Helpers\NewsModelHelper;
use Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel;


/**
 * Front end module "custom news list".
 */
class ModuleNewsCategoryView extends ModuleNewsList
{

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### NEWS CATEGORY VIEW ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // Set the item from the auto_item parameter
        if (!isset($_GET['items']) && Config::get('useAutoItem') && isset($_GET['auto_item']))
        {
            Input::setGet('items',Input::get('auto_item'));
        }

        $this->news_archives = $this->sortOutProtected(StringUtil::deserialize($this->news_archives));

        // Return if there are no archives
        if (!is_array($this->news_archives) || empty($this->news_archives))
        {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
        $categoryAlias = Input::get('items');

        if($categoryAlias)
        {
            $objCategory = NewsCategoriesModel::findOneBy('alias',$categoryAlias);

            if(!$objCategory)
            {
                throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
            }

            $categoryId = $objCategory->id;
        }
        else
        {
            $this->redirectToFrontendPage($this->jumpTo);
        }

        // Overwrite the page title
        global $objPage;
        $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($objCategory->title));


        $limit = null;
        $offset = intval($this->skipFirst);

        // Maximum number of items
        if ($this->numberOfItems > 0)
        {
            $limit = $this->numberOfItems;
        }

        // Handle featured news
        if ($this->news_featured == 'featured')
        {
            $blnFeatured = true;
        }
        elseif ($this->news_featured == 'unfeatured')
        {
            $blnFeatured = false;
        }
        else
        {
            $blnFeatured = null;
        }

        $this->Template->articles = array();
        $this->Template->empty    = $GLOBALS['TL_LANG']['MSC']['emptyList'];

        // Get the total number of items
        $intTotal = $this->countNewsItems($this->news_archives, $categoryId, $blnFeatured);

        if ($intTotal < 1)
        {
            return;
        }

        $total = $intTotal - $offset;

        // Split the results
        if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
        {
            // Adjust the overall limit
            if (isset($limit))
            {
                $total = min($limit, $total);
            }

            // Get the current page
            $id = 'page_n' . $this->id;
            $page = (Input::get($id) !== null) ? Input::get($id) : 1;

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
            {
                throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
            }

            // Set limit and offset
            $limit = $this->perPage;
            $offset += (max($page, 1) - 1) * $this->perPage;
            $skip = intval($this->skipFirst);

            // Overall limit
            if ($offset + $limit > $total + $skip)
            {
                $limit = $total + $skip - $offset;
            }

            // Add the pagination menu
            $objPagination = new Pagination($total, $this->perPage, Config::get('maxPaginationLinks'), $id);
            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        $objArticles = $this->fetchNewsItems($this->news_archives, $categoryId, $blnFeatured, ($limit ?: 0), $offset);

        // Add the articles
        if ($objArticles !== null)
        {
            $this->Template->articles = $this->parseArticles($objArticles);
        }

        $this->Template->archives = $this->news_archives;
    }


    /**
     * Count items
     * @param $newsArchives
     * @param $categoryId
     * @return null|static
     */
    protected function countNewsItems($newsArchives, $categoryId, $blnFeatured)
    {
        return NewsModelHelper::countPublishedByCategoryAndPids($categoryId, $newsArchives, $blnFeatured);
    }


    /**
     * Fetch items
     * @param $newsArchives
     * @param $categoryId
     * @param $limit
     * @param $offset
     * @return null|static
     */
    protected function fetchNewsItems($newsArchives, $categoryId, $blnFeatured, $limit, $offset)
    {
        return NewsModelHelper::findPublishedByCategoryAndPids($categoryId, $newsArchives, $blnFeatured, $limit, $offset);
    }
}