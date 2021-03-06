<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: helper_admin.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

require_once JPATH_ADMINISTRATOR.'/components/'.CCK_COM.'/helpers/common/admin.php';

// Helper
class Helper_Admin extends CommonHelper_Admin
{
	// addFolderClass
	public static function addFolderClass( &$css, $id, $color, $colorchar, $width = '20' )
	{
		if ( ! isset( $css[$id] ) ) {
			$bgcolor	=	$color ? ' background-color:'.$color.';' : '';
			$color		=	$colorchar ? ' color:'.$colorchar.';' : '';
			$css[$id]	=	'.folderColor'.$id.' { width: '.$width.'px; height: 14px;'.$bgcolor.$color.' padding-top:3px; padding-bottom:3px;'
						.	'vertical-align: middle; border: none; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius:10px; text-align:center; } ';
		}
	}
	
	// addInsidebox
	public static function addInsidebox( $isNew )
	{
		$prefix	=	JCck::getConfig_Param( 'development_prefix', '' );
		
		return ( $isNew && $prefix ) ? '<span class="insidebox">'.$prefix.'</span>' : '';
	}
	
	// addSubmenu
	public static function addSubmenu( $option, $vName )
	{
		$addons		=	array();
		$alert		=	'';
		$items		=	array();
		$uix		=	JCck::getUIX();
		
		if ( JCck::on() ) {
			$folder	=	JText::_( 'COM_CCK_'._C0_TEXT.'S' );
			
			if ( $uix == 'compact' ) {
				$items	=	array( array( 'name'=>$folder, 'link'=>_C0_LINK, 'active'=>( $vName == _C0_NAME ) ),
								   array( 'val'=>'2', 'pre'=>'', 'key'=>'COM_CCK_' ) );
			} else {
				$items	=	array( array( 'name'=>$folder, 'link'=>_C0_LINK, 'active'=>( $vName == _C0_NAME ) ),
								   array( 'val'=>'2', 'pre'=>'-&nbsp;', 'key'=>'COM_CCK_' ),
								   array( 'val'=>'3', 'pre'=>'-&nbsp;', 'key'=>'' ),
								   array( 'val'=>'4', 'pre'=>'-&nbsp;', 'key'=>'COM_CCK_' ),
								   array( 'val'=>'1', 'pre'=>'-&nbsp;', 'key'=>'' ),
								   array( 'val'=>'5', 'pre'=>'', 'key'=>'' ) );
			}
			if ( $vName == 'cck' ) {
				$addons	=	JCckDatabase::loadObjectList( 'SELECT a.title, a.link, b.element FROM #__menu AS a LEFT JOIN #__extensions AS b ON b.extension_id = a.component_id'
														. ' WHERE a.link LIKE "index.php?option=com_cck\_%" ORDER BY a.title ASC' );
				$alert	=	'<div class="alert alert-success"><a href="http://jed.seblod.com" target="_blank" class="close"><i class="icon-share"></i></a>'
						.	'<h4 class="alert-heading">Spread the World</h4>'
						.	'<div><p>If you use SEBLOD, please post a review at the JED.</p>Thank you.</div></div>';
			}
		} else {
			$folder	=	'<img src="'.JROOT_MEDIA_CCK.'/images/12/icon-12-folders.png" border="0" alt=" " width="12" height="12" />';
			
			if ( $uix == 'compact' ) {
				$items	=	array( array( 'val'=>'2', 'pre'=>'', 'key'=>'COM_CCK_' ),
								   array( 'name'=>$folder, 'link'=>_C0_LINK, 'active'=>( $vName == _C0_NAME ) ) );
			} else {
				$items	=	array( array( 'val'=>'2', 'pre'=>'', 'key'=>'COM_CCK_' ),
								   array( 'val'=>'3', 'pre'=>'', 'key'=>'' ),
								   array( 'val'=>'4', 'pre'=>'', 'key'=>'COM_CCK_' ),
								   array( 'val'=>'1', 'pre'=>'', 'key'=>'' ),
								   array( 'name'=>$folder, 'link'=>_C0_LINK, 'active'=>( $vName == _C0_NAME ) ),
								   array( 'val'=>'5', 'pre'=>'', 'key'=>'' ) );
			}
		}
		
		self::addSubmenuEntries( $option, $vName, $items, $addons, $alert );
	}
	
	// addToolbar
	public static function addToolbar( $vName, $vTitle, $folderId = 0 )
	{
		$bar	=	JToolBar::getInstance( 'toolbar' );
		$canDo	=	self::getActions( $folderId );
		$uix	=	JCck::getUIX();
		
		require_once JPATH_COMPONENT.'/helpers/toolbar/separator.php';
		
		if ( $vTitle != '' ) {
			JToolBarHelper::title( JText::_( $vTitle.'_MANAGER' ), $vName.'s.png' );
		}
		if ( $canDo->get( 'core.create' ) || $canDo->get( 'core.edit' ) ) {
			if ( $canDo->get( 'core.create' ) ) {
				if ( $vName == 'type' || $vName == 'search' ) {
					$user	=	JCck::getUser();
					$check	=	'preferences_'.$vName.'s_splash_screen';
					if ( ! @$user->$check ) {
						if ( JCck::on() ) {
							JHtml::_( 'bootstrap.modal', 'collapseModal' );
							$label	=	JText::_( 'JTOOLBAR_NEW' );
							$html	=	'<button data-toggle="modal" data-target="#collapseModal2" class="btn btn-small btn-success">'
									.	'<i class="icon-new" title="'.$label.'"></i> '.$label.'</button>';
							$bar->appendButton( 'Custom', $html, 'new' );
						} else {
							require_once JPATH_COMPONENT.'/helpers/toolbar/modalbox.php';
							$bar->appendButton( 'CckModalBox', 'new', 'JTOOLBAR_NEW', 'index.php?option='.CCK_COM.'&view='.$vName.'&layout=new&tmpl=component' );
						}
					} else {
						JToolBarHelper::custom( $vName.'.add', 'new', 'new', 'JTOOLBAR_NEW', false );
					}
				} else {
					JToolBarHelper::custom( $vName.'.add', 'new', 'new', 'JTOOLBAR_NEW', false );
				}
			}
			if ( $canDo->get( 'core.edit' ) ) {
				JToolBarHelper::custom( $vName.'.edit', 'edit', 'edit', 'JTOOLBAR_EDIT', true );
			}
			$bar->appendButton( 'CckSeparator' );
		}
		if ( $canDo->get( 'core.edit.state' ) || $canDo->get( 'core.delete' ) ) {
			if ( $canDo->get( 'core.edit.state' ) ) {
				JToolBarHelper::custom( $vName.'s'.'.publish', 'publish', 'publish', 'COM_CCK_TURN_ON', true );
				JToolBarHelper::custom( $vName.'s'.'.unpublish', 'unpublish', 'unpublish', 'COM_CCK_TURN_OFF', true );
			}
			if ( $canDo->get( 'core.delete' ) ) {
				JToolBarHelper::custom( $vName.'s'.'.delete', 'delete', 'delete', 'JTOOLBAR_DELETE', true );
			}
			if ( $canDo->get( 'core.edit.state' ) ) {
				JToolBarHelper::custom( $vName.'s'.'.checkin', 'checkin', 'checkin', 'JTOOLBAR_CHECKIN', true );
			}
			if ( $vName == 'type' || $vName == 'search' ) {
				JToolBarHelper::custom( $vName.'s'.'.version', 'archive', 'archive', 'JTOOLBAR_ARCHIVE', true );
			}
			if ( !( $vName == 'folder' || $vName == 'site' ) ) {
				$bar->appendButton( 'CckSeparator' );
			}
		}
		if ( JCck::on() && $vName != 'site' && $vName != 'folder' && $canDo->get('core.edit' ) ) {
			JHtml::_( 'bootstrap.modal', 'collapseModal' );
			$label	=	JText::_( 'JTOOLBAR_BATCH' );
			$html	=	'<button data-toggle="modal" data-target="#collapseModal" class="btn btn-small">'
					.	'<i class="icon-checkbox-partial" title="'.$label.'"></i> '.$label.'</button>';
			$bar->appendButton( 'Custom', $html, 'batch' );
		}
		if ( $vName == 'folder' ) {
			JToolBarHelper::custom( 'folders.clear', 'refresh', 'refresh', JText::_( 'COM_CCK_CLEAR_ACL' ), true );
			JToolBarHelper::custom( 'folders.rebuild', 'refresh', 'refresh', JText::_( 'COM_CCK_REBUILD' ), false );
			if ( JCck::on() ) {
				JHtml::_( 'bootstrap.modal', 'collapseModal' );
				$label	=	JText::_( 'COM_CCK_APP_FOLDER_EXPORT_OPTIONS' );
				$html	=	'<button data-toggle="modal" data-target="#collapseModal" class="btn btn-small">'
						.	'<i class="icon-checkbox-partial" title="'.$label.'"></i> '.$label.'</button>';
				$bar->appendButton( 'Custom', $html, 'batch' );
			} else {
				$bar->appendButton( 'CckSeparator' );
				require_once JPATH_COMPONENT.'/helpers/toolbar/scroll.php';
				$bar->appendButton( 'CckScroll', 'download', JText::_( 'COM_CCK_DOWNLOAD' ), '#pagination-bottom' );
			}
		} elseif ( $vName == 'site' ) {
			//JToolBarHelper::custom( 'sites.clear', 'refresh', 'refresh', JText::_( 'COM_CCK_CLEAR_VISITS' ), true );
		} else {
			JToolBarHelper::custom( 'folders', 'folder', 'folder', JText::_( 'COM_CCK_'._C0_TEXT.'S' ), false );
		}
	}
	
	// addToolbarEdit
	public static function addToolbarEdit( $vName, $vTitle, $vMore = '', $params = array() )
	{
		$bar		=	JToolBar::getInstance( 'toolbar' );
		$user		=	JFactory::getUser();
		$userId		=	$user->get( 'id' );
		$checkedOut	= 	! ( $vMore['checked_out'] == 0 || $vMore['checked_out'] == $userId );
		$canDo		=	self::getActions( $vMore['folder'] );
		$vSubtitle	=	'';
		
		JFactory::getApplication()->input->set( 'hidemainmenu', true );
		require_once JPATH_COMPONENT.'/helpers/toolbar/separator.php';
		
		if ( ( $vName == 'type' || $vName == 'search' ) ) {
			$vSubtitle	=	' <span class="subtitle">[ '.JText::_( 'COM_CCK_SEBLOD_WORKSHOP' ).' ]</span>';
			require_once JPATH_COMPONENT.'/helpers/toolbar/link.php';
			if ( !JCck::on() ) {
				$bar->appendButton( 'CckLink', 'eye-open', JText::_( 'COM_CCK_POSITIONS' ), 'javascript:JCck.Dev.previewPositions();' );
				$bar->appendButton( 'CckSeparator' );
			}
		}
		if ( $vMore['isNew'] )  {
			JToolBarHelper::title( JText::_( $vTitle ).': <small><small>[ '.JText::_( 'COM_CCK_ADD' ).' ]'.$vSubtitle.'</small></small>', $vName.'s.png' );
			
			if ( $canDo->get('core.create') ) {
				JToolBarHelper::custom( $vName.'.apply', 'apply', 'apply', 'JTOOLBAR_APPLY', false );
				JToolBarHelper::custom( $vName.'.save', 'save', 'save', 'JTOOLBAR_SAVE', false );
				JToolBarHelper::custom( $vName.'.save2new', 'save-new', 'save-new', 'JTOOLBAR_SAVE_AND_NEW', false );
			}
			JToolBarHelper::custom( $vName.'.cancel', 'cancel', 'cancel', 'JTOOLBAR_CANCEL', false );
		} else {
			JToolBarHelper::title( JText::_( $vTitle ).': <small><small>[ '.JText::_( 'JTOOLBAR_EDIT' ).' ]'.$vSubtitle.'</small></small>', $vName.'s.png' );
			
			if ( !$checkedOut ) {
				if ( $canDo->get('core.edit') ) {
					JToolBarHelper::custom( $vName.'.apply', 'apply', 'apply', 'JTOOLBAR_APPLY', false );
					JToolBarHelper::custom( $vName.'.save', 'save', 'save', 'JTOOLBAR_SAVE', false );
					if ( $canDo->get('core.create' ) ) {
						JToolBarHelper::custom( $vName.'.save2new', 'save-new', 'save-new', 'JTOOLBAR_SAVE_AND_NEW', false );
					}
				}
			}
			if ( ! $vMore['isNew'] && $canDo->get( 'core.create' ) && $vName == 'folder' ) {
				JToolBarHelper::custom( $vName.'.save2copy', 'save-copy', 'save-copy', 'JTOOLBAR_SAVE_AS_COPY', false );
				//if ( @$params['rename'] ) { //Todo
				//	JToolBarHelper::custom( $vName.'.save2copy', 'save-copy', 'save-copy', 'JTOOLBAR_SAVE_AS_COPY', false );
				//}
			}
			JToolBarHelper::custom( $vName.'.cancel', 'cancel', 'cancel', 'JTOOLBAR_CLOSE', false );
		}
		if ( ( $vName == 'type' || $vName == 'search' ) && JCck::on() ) {
			$bar->appendButton( 'CckLink', 'eye-open', JText::_( 'COM_CCK_POSITIONS' ), 'javascript:JCck.Dev.previewPositions();' );
		}
	}
	
	// addToolbarDelete
	public static function addToolbarDelete( $vName, $vTitle )
	{
		JFactory::getApplication()->input->set( 'hidemainmenu', true );
		
		JToolBarHelper::title( JText::_( $vTitle ).': <small><small>[ '.JText::_( 'Delete' ).' ]</small></small>', $vName.'s.png' );
		JToolBarHelper::custom( $vName.'cancel', 'cancel', 'cancel', 'JTOOLBAR_CLOSE', false );
	}
	
	// getActions
	public static function getActions( $folderId = 0 )
	{
		$user	=	JFactory::getUser();
		$result	=	new JObject;
		
		if ( empty( $folderId ) ) {
			$assetName	=	'com_'.CCK_NAME;
		} else {
			$assetName	=	'com_'.CCK_NAME.'.folder.'.(int)$folderId;
		}
		
		$actions	=	array( 'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete' );
		foreach ( $actions as $action ) {
			$result->set( $action, $user->authorise( $action, $assetName ) );
		}
		
		return $result;
	}
}
?>