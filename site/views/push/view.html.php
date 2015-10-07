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
class PushViewPush extends JViewLegacy
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
		// Assign data to the view
    $articleId = 6;
    JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');
    $articleModel = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
    $articlesModel = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));


    $appParams = JFactory::getApplication()->getParams();
    $articleModel->setState('params', $appParams);
    $articlesModel->setState('params', $appParams);

    //$article = $articleModel->getItem($articleId);
    $articles = $articlesModel->getItems();

    $articleArray = [];
    foreach($articles as $article){
      $articleArray[] = ['title' => $article->title, 'introtext' => $article->introtext];
    }

    $this->msg = json_encode($articleArray);

		// Display the view
		parent::display($tpl);
	}
}
