<?php

namespace Doublespark\NewsCategoriesBundle\Modules;

use Contao\BackendTemplate;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\Environment;
use Contao\Input;
use Contao\Model\Collection;
use Contao\ModuleNewsList;
use Contao\NewsModel;
use Contao\System;
use Doublespark\NewsCategoriesBundle\Helpers\NewsModelHelper;
use Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel;

class ModuleNewsCategoryView extends ModuleNewsList
{
    protected int $categoryId = 0;

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

            $objTemplate->wildcard = '### NEWS CATEGORY VIEW ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // Set the item from the auto_item parameter
        $categoryAlias = Input::get('auto_item');

        if(empty($categoryAlias))
        {
            throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
        }

        $objNewsCategory = NewsCategoriesModel::findByIdOrAlias($categoryAlias);

        if($objNewsCategory)
        {
            $this->categoryId = $objNewsCategory->id;

            // Overwrite the page metadata
            $responseContext = System::getContainer()->get('contao.routing.response_context_accessor')->getResponseContext();

            if ($responseContext && $responseContext->has(HtmlHeadBag::class))
            {
                /** @var HtmlHeadBag $htmlHeadBag */
                $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
                $htmlDecoder = System::getContainer()->get('contao.string.html_decoder');

                $htmlHeadBag->setTitle('News Category - '.$htmlDecoder->inputEncodedToPlainText($objNewsCategory->title));

                if($objNewsCategory->description)
                {
                    $htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($objNewsCategory->description));
                }
            }
        }
        else
        {
            // Category does not exist
            throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
        }

        return parent::generate();
    }

    /**
     * Count the total matching items
     *
     * @param array   $newsArchives
     * @param boolean $blnFeatured
     *
     * @return integer
     */
    protected function countItems($newsArchives, $blnFeatured): int
    {
        return NewsModelHelper::countPublishedByCategoryAndPids($this->categoryId, $newsArchives, $blnFeatured);
    }

    /**
     * Fetch the matching items
     *
     * @param array   $newsArchives
     * @param boolean $blnFeatured
     * @param integer $limit
     * @param integer $offset
     *
     * @return Collection|NewsModel|null
     */
    protected function fetchItems($newsArchives, $blnFeatured, $limit, $offset)
    {
        return NewsModelHelper::findPublishedByCategoryAndPids($this->categoryId, $newsArchives, $blnFeatured, $limit, $offset);
    }
}