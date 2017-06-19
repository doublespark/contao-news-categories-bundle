<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Doublespark\NewsCategoriesBundle\Helpers;

use Model\Collection;

/**
 * Class NewsModelHelper
 * @package Doublespark\NewsCategoriesBundle\Models
 */
class NewsModelHelper
{
    /**
     * Fetch news stories by PID and category
     * @param $categoryId
     * @param $arrPids
     * @param int $intLimit
     * @param int $intOffset
     * @return null|static
     */
    public static function findPublishedByCategoryAndPids($categoryId, $arrPids, $blnFeatured, $intLimit=0, $intOffset=0)
    {
        if (!is_array($arrPids))
        {
            return null;
        }

        // Verify all IDs are numeric
        foreach($arrPids as $k => $v)
        {
            if(!is_numeric($v))
            {
                unset($arrPids[$k]);
            }
        }

        if(count($arrPids) < 1)
        {
            return null;
        }

        // Build SQL
        $sql = "SELECT * FROM tl_news
                INNER JOIN tl_news_category ON tl_news.id = tl_news_category.news_id
                WHERE pid IN(".implode(',',$arrPids).")
                AND category_id = ?
                AND published=1";

        if ($blnFeatured === true)
        {
            $sql .= " tl_news.featured='1'";
        }
        elseif ($blnFeatured === false)
        {
            $sql .= " tl_news.featured=''";
        }

        // Ordering
        $sql .= " ORDER BY date DESC";

        // Apply limit
        if(is_numeric($intLimit) AND $intLimit > 0)
        {
            $sql .= " LIMIT $intLimit";
        }

        // Apply offset
        if(is_numeric($intOffset) AND $intOffset > 0)
        {
            $sql .= " OFFSET $intOffset";
        }

        $objResult = \Database::getInstance()->prepare($sql)->execute($categoryId);

        if($objResult->numRows < 1)
        {
            return null;
        }

        return Collection::createFromDbResult($objResult, 'tl_news');
    }

    /**
     * Count news stories by PID and category
     * @param $categoryId
     * @param $arrPids
     * @return null|static
     */
    public static function countPublishedByCategoryAndPids($categoryId, $arrPids, $blnFeatured)
    {
        if (!is_array($arrPids))
        {
            return null;
        }

        // Verify all IDs are numeric
        foreach($arrPids as $k => $v)
        {
            if(!is_numeric($v))
            {
                unset($arrPids[$k]);
            }
        }

        if(count($arrPids) < 1)
        {
            return null;
        }

        // Build SQL
        $sql = "SELECT COUNT(*) AS count FROM tl_news
                INNER JOIN tl_news_category ON tl_news.id = tl_news_category.news_id
                WHERE pid IN(".implode(',',$arrPids).")
                AND category_id = ?
                AND published=1";

        if ($blnFeatured === true)
        {
            $sql .= " tl_news.featured='1'";
        }
        elseif ($blnFeatured === false)
        {
            $sql .= " tl_news.featured=''";
        }

        $objResult = \Database::getInstance()->prepare($sql)->execute($categoryId);

        return $objResult->count;
    }
}