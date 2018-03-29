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
class PushViewMenus extends JViewLegacy
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
    $query->select('*');
    $query->from('#__menu');
    $query->where('id="'.$id.'"');
   
    
    $db->setQuery((string)$query);

    // Load the results as a list of stdClass objects (see later for more options on retrieving data).
    $results = $db->loadObjectList();

    $responseArray = [];
    $menuItemsArray = [];
    foreach($results as $menu_item){
      if($menu_item->fulltext == ""){
        $menu_item->fulltext = null;
      }

      $menuItemsArray[] = ['menu_type' => $menu_item->menutype,
                         'title' => $menu_item->title,
                         'alias' => $menu_item->fulltext,
                         'path' => $menu_item->path,
                         'link' => $menu_item->link,
                         'id' => $menu_item->id,
                         'parent_id' => $menu_item->parent_id,
                         'level' => $menu_item->level,
                         'component_id' => $menu_item->component_id,
                         'checked_out' => $menu_item->checked_out,
                         'checked_out_time' => $menu_item->checked_out_time,
                         'browserNav' => $menu_item->browserNav,
                         'access' => $menu_item->access,
                         'img' => $menu_item->img,
                         'template_style_id' => $menu_item->template_style_id,
                         'params' => $menu_item->params,
                         'lft' => $menu_item->lft,
                         'rgt' => $menu_item->rgt,
                         'home' => $menu_item->home,
                         'language' => $menu_item->language,
                         'client_id' => $menu_item->client_id
                        ];
    }

    $responseArray['start_date'] = null;
    $responseArray['end_date'] = null;
    $responseArray['total_items'] = count($results);
    $responseArray['total_pages'] = ceil(count($results)/$limit);
    $responseArray['page'] = $page;
    $responseArray['results'] = $menuItemsArray;
    //echo var_dump($results);
    echo json_encode($responseArray);
	}
}
