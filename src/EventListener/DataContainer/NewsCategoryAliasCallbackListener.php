<?php

namespace Doublespark\NewsCategoriesBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Slug\Slug;
use Contao\Database;
use Contao\DataContainer;
use \Exception;

#[AsCallback(table: 'tl_news_categories', target: 'fields.alias.save', priority: 100)]
class NewsCategoryAliasCallbackListener
{
    /**
     * @param Slug $slug
     */
    public function __construct(protected Slug $slug){}

    /**
     * Generate alias
     * @param string $varValue
     * @param DataContainer $dc
     * @return string
     * @throws Exception
     */
    public function __invoke(string $varValue, DataContainer $dc): string
    {
        $aliasExists = function (string $alias) use ($dc): bool
        {
            return Database::getInstance()->prepare("SELECT id FROM tl_news_categories WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue)
        {
            $varValue = $this->slug->generate($dc->activeRecord->title, [], $aliasExists);
        }
        elseif (preg_match('/^[1-9]\d*$/', $varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        }
        elseif ($aliasExists($varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }
}