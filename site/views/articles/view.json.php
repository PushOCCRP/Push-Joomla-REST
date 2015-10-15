<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class PushViewArticles extends JViewLegacy
{
	/**
	 * Display the Push view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

    //Get limit, default is 10
    $limit = JRequest::getVar('limit');

    if(!$limit) {
      $limit = 10;
    }

    // Get a db connection.
    $db = JFactory::getDbo();

    // Create a new query object.
    $query = $db->getQuery(true);

    // Select all records from the user profile table where key begins with "custom.".
    // Order it by the ordering field.
    $query->select('*');
    $query->from($db->quoteName('#__content'));
    $query->order('ordering ASC');
    $query->setLimit($limit);
    $query->order('ordering ASC');
    //
    // Reset the query using our newly populated query object.
    $db->setQuery($query);

    // Load the results as a list of stdClass objects (see later for more options on retrieving data).
    $results = $db->loadObjectList();

    $responseArray = [];
    $articleArray = [];
    foreach($results as $article){
      if($article->fulltext == ""){
        $article->fulltext = null;
      }

      $articleArray[] = ['headline' => $article->title,
                         'description' => $article->introtext,
                         'body' => $article->fulltext,
                         'author' => $article->created_by_alias,
                         'publish_date' => $article->publish_up,
                         'id' => $article->id,
                         'language' => $article->language
                        ];
    }

    $responseArray['start_date'] = null;
    $responseArray['end_date'] = null;
    $responseArray['total_items'] = $limit;
    $responseArray['total_pages'] = '1';
    $responseArray['page'] = '1';
    $responseArray['results'] = $articleArray;
    //echo var_dump($results);
    echo json_encode($responseArray);
	}
}
