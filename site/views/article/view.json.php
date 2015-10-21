<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_push
 *
 * @copyright   Copyright (C) 2015 - International Center For Journalists
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.4
 */
class PushViewArticle extends JViewLegacy
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
    $article_id = JRequest::getVar('id');

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
    $query->where("id = " . $article_id);
    $query->setLimit(1);
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

    $responseArray['total_items'] = '1';
    $responseArray['total_pages'] = '1';
    $responseArray['page'] = '1';
    $responseArray['results'] = $articleArray;
    //echo var_dump($results);
    echo json_encode($responseArray);
	}
}
