<?php

namespace Doublespark\NewsCategoriesBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Database;
use Contao\DataContainer;

#[AsCallback(table: 'tl_news', target: 'config.onsubmit', priority: 100)]
class NewsSubmitCallbackListener
{
    /**
     * @param DataContainer $dc
     */
    public function __invoke(DataContainer $dc): void
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord)
        {
            return;
        }

        // Clear previous relations
        Database::getInstance()->prepare('DELETE FROM tl_news_category WHERE news_id=?')->execute($dc->id);

        $arrCategories = [];

        // Check if we have a serialized value
        if(!is_array($dc->activeRecord->categories) AND !empty($dc->activeRecord->categories))
        {
            $arrCategories = unserialize($dc->activeRecord->categories);
        }

        if(is_array($dc->activeRecord->categories))
        {
            $arrCategories = $dc->activeRecord->categories;
        }

        $values       = [];
        $placeholders = [];

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
            Database::getInstance()->prepare("INSERT INTO tl_news_category (news_id, category_id) VALUES ".implode(',',$placeholders))->execute(...$values);
        }
    }
}