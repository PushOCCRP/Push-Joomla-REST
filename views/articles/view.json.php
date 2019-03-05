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
    $language = JRequest::getVar('language');
    $categorized = JRequest::getVar('categorized');
    $categories = Jrequest::getVar('categories');

    onAfterInitialise();
    if(!$limit) {
      $limit = 10;
    }

    if(!$page) {
      $page = 0;
    }

    // Get a db connection.
    $db = JFactory::getDbo();


    // Create a new query object.
    $query = $db->getQuery(true);

    // Select all records from the user profile table where key begins with "custom.".
    // Order it by the ordering field.
    $query->select('*');
    $query->from('#__menu');
    $query->where('menutype="main" && language="'.$language.'" && type!="url"');
    //state = 1 AND language = 'en-GB'
    
    //echo 'menutype="main" && language="'.$language.'" && type!="url" || menutype="topleft" && language="'.$language.'" && type!="url"';
    


    $db->setQuery((string)$query);

    // Load the results as a list of stdClass objects (see later for more options on retrieving data).
    $results = $db->loadObjectList();


    //echo json_encode($results);

    $responseArray = [];
    $menuItemsArray = [];
    $menuItemsTitles = [];
    foreach($results as $menu_item){
      if($menu_item->fulltext == ""){
        $menu_item->fulltext = null;
      }

      $categoryIds = ((isset(json_decode($menu_item->params)->featured_categories)) ? json_decode($menu_item->params)->featured_categories : intval(filter_var($menu_item->link, FILTER_SANITIZE_NUMBER_INT))) ;
      //echo '<pre>'; print_r($categoryIds); echo '</pre>';
      $temp = returnArticles($categoryIds, $language);
      if(count($temp)!==0){
        $menuItemsArray["$menu_item->title"] = $temp;
        $menuItemsTitles[] = $menu_item->title;
      }
    }

    $responseArray['start_date'] = null;
    $responseArray['end_date'] = null;
    $responseArray['total_items'] = count($results);
    $responseArray['total_pages'] = ceil(count($results)/$limit);
    $responseArray['page'] = $page;
    $responseArray['results'] = $menuItemsArray;
    $responseArray['categories'] = $menuItemsTitles;

    
    //$responseArray['categories'] = explode(",",$categories);
    //echo var_dump($results);
    echo json_encode($responseArray);
    
  }
  




}

function returnArticles($categoryIds , $lang){

  $db = JFactory::getDbo();
  $responseArray = [];
  
  if(count($categoryIds) != 1 || empty($categoryIds[0]) && is_array($categoryIds)){
    
    $page = 0;
    $limit = 10;



    

    // Create a new query object.
    $query = $db->getQuery(true);


    $query->select('*');
    $query->from('#__content');
    $query->where('featured="1" && state="1" && language="'.$lang.'"');
    $query->order($db->quoteName('modified') . ' DESC limit '.$page*$limit.','.$limit.'');

    $db->setQuery((string)$query);


    $results = $db->loadObjectList();

     
    

    
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
    $responseArray = $articleArray;

  }else{
    $page = 0;
    $limit = 10;




 
    // Create a new query object.
    $query = $db->getQuery(true);


    $query->select('*');
    $query->from('#__content');
    $query->where('catid="'.$categoryIds.'" && language="'.$lang.'"');
    $query->order($db->quoteName('created') . ' DESC limit '.$page*$limit.','.$limit.'');

    $db->setQuery((string)$query);


    $results = $db->loadObjectList();


    
    $articleArray = [];
    foreach($results as $article){
      if($article->fulltext == ""){
        $article->fulltext = null;
      }

      $articleNew = ['headline' => $article->title,
                         'description' => $article->introtext,
                         'body' => $article->fulltext,
                         'author' => $article->created_by_alias,
                         'publish_date' => $article->publish_up,
                         'id' => $article->id,
                         'language' => $article->language,
                         'categoryIds' => $categoryIds
                        ];
                        $responseArray[] = $articleNew;
                      }
    
   

   
  }
  return $responseArray;
}


function onAfterInitialise()
{
  /*$lang = JFactory::getLanguage();
  $bla = $lang->setLanguage('en-GB');
  $lang->load();
  $leng = JFactory::getLanguage();
  echo 'language is: "'.$leng.'"';*/

}
