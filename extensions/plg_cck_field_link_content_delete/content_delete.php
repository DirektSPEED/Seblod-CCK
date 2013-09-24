<?php
/**
* @version 			SEBLOD 3.x Core
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// Plugin
class plgCCK_Field_LinkContent_Delete extends JCckPluginLink
{
	protected static $type	=	'content_delete';
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// onCCK_Field_LinkPrepareContent
	public static function onCCK_Field_LinkPrepareContent( &$field, &$config = array() )
	{		
		if ( self::$type != $field->link ) {
			return;
		}
		
		// Prepare
		$link	=	parent::g_getLink( $field->link_options );
		
		// Set
		$field->link	=	'';
		self::_link( $link, $field, $config );
	}
	
	// _link
	protected static function _link( $link, &$field, &$config )
	{
		$app			=	JFactory::getApplication();
		$form			=	$config['type'];
		$id				=	$config['id'];
		$itemId			=	$link->get( 'itemid', $app->input->getInt( 'Itemid', 0 ) );
		$task			=	( JFactory::getApplication()->isAdmin() ) ? 'list.delete' : 'delete';
		$uri			=	JFactory::getURI();
		$return			=	base64_encode( $uri );
		
		// Check
		$user 			=	JCck::getUser();
		$canDelete		=	$user->authorise( 'core.delete', 'com_cck.form.'.$config['type_id'] );
		$canDeleteOwn	=	$user->authorise( 'core.delete.own', 'com_cck.form.'.$config['type_id'] );
		if ( ( !$canDelete && !$canDeleteOwn ) ||
			 ( !$canDelete && $canDeleteOwn && $config['author'] != $user->get( 'id' ) ) ||
			 ( $canDelete && !$canDeleteOwn && $config['author'] == $user->get( 'id' ) ) ) {
			if ( !$link->get( 'no_access', 1 ) ) {
				$field->display	=	0;
			}
			return;
		}
		
		// Prepare
		$link_class		=	$link->get( 'class', '' );
		$link_title		=	$link->get( 'title', '' );
		$link_title2	=	$link->get( 'title_custom', '' );
		
		$field->link		=	'index.php?option=com_cck&task='.$task.'&cid='.$id.'&Itemid='.$itemId.'&return='.$return;
		$field->link		=	JRoute::_( $field->link );
		$field->link_class	=	$link_class ? $link_class : ( isset( $field->link_class ) ? $field->link_class : '' );
		if ( $link->get( 'confirm', 1 ) ) {
			$field->link_onclick	=	'if(!confirm(\''.addslashes( JText::_( 'COM_CCK_CONFIRM_DELETE' ) ).'\')){return false;}';
		}
		$field->link_title	=	$link_title ? ( $link_title == '2' ? $link_title2 : ( isset( $field->link_title ) ? $field->link_title : '' ) ) : '';
	}
}
?>