<?php

namespace Doublespark\NewsCategoriesBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Template;
use Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel;

/**
 * Add categories to news template
 * @Hook("parseTemplate")
 */
class ParseTemplateListener
{
    public function __invoke(Template $template): void
    {
        if(str_starts_with($template->getName(),'news_'))
        {
            if($template->categories)
            {
                $arrCategoryIds = unserialize($template->categories);

                if(is_array($arrCategoryIds))
                {
                    $objCategories = NewsCategoriesModel::findMultipleByIds($arrCategoryIds);

                    if($objCategories)
                    {
                        $arrCategories = [];

                        while($objCategories->next())
                        {
                            $arrCategories[$objCategories->id] = $objCategories->title;
                        }

                        $template->categories = $arrCategories;
                    }
                }
            }
        }
    }
}