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
class PushViewCategory_articles extends JViewLegacy
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
    $id = JRequest::getVar('id');
    $page = JRequest::getVar('page');

    if(!$limit) {
      $limit = 10;
    }

    // Get a db connection.
    $db = JFactory::getDbo();
   
    // Create a new query object.
    $query = $db->getQuery(true);

    // Select all records from the user profile table where key begins with "custom.".
    // Order it by the ordering field.
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__content');
    $query->where('catid="'.$id.'"');
    $query->order($db->quoteName('created') . ' DESC limit '.$page*$limit.','.$limit.'');
    
    $query2 = $db->getQuery(true);
    $query2->select('COUNT(*)');
    $query2->from('#__content');
    $query2->where('catid="'.$id.'"');


    //limit "'.$page.',"'.$limit.'"
    $db->setQuery((string)$query);

    // Load the results as a list of stdClass objects (see later for more options on retrieving data).
    $results = $db->loadObjectList();
    $db->setQuery((string)$query2);

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
    $responseArray['total_items'] = $db->loadResult();
    $responseArray['total_pages'] = ceil($db->loadResult()/$limit);
    $responseArray['page'] = $page;
    $responseArray['results'] = $articleArray;
    //echo var_dump($results);
    echo json_encode($responseArray);
	}
}
