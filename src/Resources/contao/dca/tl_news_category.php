<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Table tl_news_category
 */
$GLOBALS['TL_DCA']['tl_news_category'] = array
(

	// Config
	'config' => array
	(
		'dataContainer' => 'Table',
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