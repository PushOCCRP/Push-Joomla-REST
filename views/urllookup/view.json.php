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
 * @since  0.0.2
 */
class PushViewUrllookup extends JViewLegacy
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

     // Get a db connection.
    $db = JFactory::getDbo();

    $urls = explode(',', JRequest::getVar('u'));
    $ids = [];
    $articles = [];

    foreach($urls as $url){
      //Illformatted strings are bad
      if(!$url || $url == ""){
        continue;
      }

      $router =& JApplication::getRouter();
      $router->setMode(1); // Set mode to Parse RAW URL.

      $u =& JURI::getInstance( $url );

      $routingArray = $router->parse($u);

      $ids[$url] = $routingArray['id'];
      $query = $db->getQuery(true);

      $query->select('*');
      $query->from($db->quoteName('#__content'));
      $query->where('id = '.$routingArray['id']);
      $query->setLimit(1);

      $db->setQuery($query);

      $results = $db->loadObjectList();

      $article = $results[0];

      if($article->fulltext == ""){
        $article->fulltext = null;
      }

      $articleArray = ['headline' => $article->title,
                         'description' => $article->introtext,
                         'body' => $article->fulltext,
                         'author' => $article->created_by_alias,
                         'publish_date' => $article->publish_up,
                         'id' => $article->id,
                         'language' => $article->language
                        ];

      $articles[]= $articleArray;
    }

    //echo var_dump($articles);
    echo json_encode($articles);
	}
}
