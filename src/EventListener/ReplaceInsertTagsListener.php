<?php

namespace Doublespark\NewsCategoriesBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Input;
use Doublespark\NewsCategoriesBundle\Models\NewsCategoriesModel;

/**
 * @Hook("replaceInsertTags")
 */
class ReplaceInsertTagsListener
{
    public function __invoke(string $insertTag, bool $useCache, string $cachedValue, array $flags, array $tags, array $cache, int $_rit, int $_cnt)
    {
        $tag = explode('::', $insertTag);

        if($tag[0] == 'news_category')
        {
            if($tag[1] === 'title')
            {
                // Set the item from the auto_item parameter
                $categoryAlias = Input::get('auto_item');

                $objNewsCategory = NewsCategoriesModel::findByIdOrAlias($categoryAlias);

                if($objNewsCategory)
                {
                    return $objNewsCategory->title;
                }
            }
        }

        return false;
    }
}