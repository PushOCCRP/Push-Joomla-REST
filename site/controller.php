<?php
/**
* @package     Joomla.Administrator
* @subpackage  com_push
*
* @copyright   Copyright (C) 2015 International Center For Journalists
* @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * Push Component Controller
 *
 * @since  0.0.1
 */
class PushController extends JControllerLegacy
{
  function articles() {
      $view = $this->getView( 'articles', 'json' );
      $view->setLayout( 'default' );
      $view->display();
  }

  function url_lookup() {
      $view = $this->getView( 'urllookup', 'json');
      $view->setLayout( 'default' );
      $view->display();
  }
}
