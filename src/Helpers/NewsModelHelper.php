<?php

namespace Doublespark\NewsCategoriesBundle\Helpers;

use Contao\Database;
use Contao\Date;
use Contao\Model\Collection;
use Contao\NewsModel;

class NewsModelHelper extends NewsModel
{
    public static function findPublishedByCategoryAndPids(int $categoryId, $arrPids, $blnFeatured=null, $intLimit=0, $intOffset=0)
    {
        if (empty($arrPids) || !\is_array($arrPids))
        {
            return null;
        }

        $t = static::$strTable;

        $time = Date::floorToMinute();

        $sql = "SELECT * FROM $t
                INNER JOIN tl_news_category ON $t.id = tl_news_category.news_id
                WHERE $t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")
                AND category_id=?
                AND $t.published=1 AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";

        if ($blnFeatured === true)
        {
            $sql .= " $t.featured='1'";
        }
        elseif ($blnFeatured === false)
        {
            $sql .= " $t.featured=''";
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

        $objResult = Database::getInstance()->prepare($sql)->execute($categoryId);

        if(!$objResult)
        {
            return null;
        }

        return Collection::createFromDbResult($objResult, $t);
    }

    public static function countPublishedByCategoryAndPids(int $categoryId, $arrPids, $blnFeatured=null)
    {
        if (empty($arrPids) || !\is_array($arrPids))
        {
            return 0;
        }

        $t = static::$strTable;

        $time = Date::floorToMinute();

        $sql = "SELECT COUNT(*) AS count FROM $t
                INNER JOIN tl_news_category ON $t.id = tl_news_category.news_id
                WHERE $t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")
                AND category_id=?
                AND $t.published=1 AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";

        if ($blnFeatured === true)
        {
            $sql .= " $t.featured='1'";
        }
        elseif ($blnFeatured === false)
        {
            $sql .= " $t.featured=''";
        }

        $objResult = Database::getInstance()->prepare($sql)->execute($categoryId);

        return $objResult->count;
    }
}