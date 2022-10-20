<?php

use Contao\DC_Table;

/**
 * Table tl_news_category
 */
$GLOBALS['TL_DCA']['tl_news_category'] = array
(

	// Config
	'config' => array
	(
		'dataContainer' => DC_Table::class,
		'sql' => array
		(
			'keys' => array
			(
				'news_id,category_id' => 'index'
			)
		)
	),

	// Fields
	'fields' => array
	(
        'category_id' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'news_id' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        )
	)
);