<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: controller.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// Controller
class CCKController extends JControllerLegacy
{
	protected $text_prefix	=	'COM_CCK';
	
	// display
	public function display( $cachable = false, $urlparams = false )
	{
		parent::display( true );
	}
	
	// ajax
	public function ajax()
    {
		$app	=	JFactory::getApplication();
		$file	=	$app->input->getString( 'file', '' );
		$file	=	JPATH_SITE.'/'.$file;
		
		if ( is_file( $file ) ) {
			include_once $file;
		}
	}
	
	// cancel
	public function cancel( $key = 'config' )
	{
		JSession::checkToken() or jexit( JText::_( 'JINVALID_TOKEN' ) );
		
		$app	=	JFactory::getApplication();
		$config	=	$app->input->post->get( $key, array(), 'array' );
		$id		=	(int)$config['id'];
		
		if ( $id > 0 ) {
			$core	=	JCckDatabase::loadObject( 'SELECT pk, storage_location as location FROM #__cck_core WHERE id = '.(int)$id );
			if ( $core->location != '' ) {
				require_once JPATH_SITE.'/plugins/cck_storage_location/'.$core->location.'/'.$core->location.'.php';
				JCck::callFunc( 'plgCCK_Storage_Location'.$core->location, 'checkIn', $core->pk );
			}
		}
		
		$this->setRedirect( $this->_getReturnPage() );
	}
	
	// delete
	public function delete()
	{
		// JSession::checkToken() or jexit( JText::_( 'JINVALID_TOKEN' ) );
		
		$app	=	JFactory::getApplication();
		$model	=	$this->getModel( 'list' );
		$cid	=	$app->input->get( 'cid', array(), 'array' );
		
		jimport('joomla.utilities.arrayhelper');
		JArrayHelper::toInteger( $cid );
		
		if ( $nb = $model->delete( $cid ) ) {
			$msg		=	JText::_( 'COM_CCK_SUCCESSFULLY_DELETED' ); // todo: JText::plural( 'COM_CCK_N_SUCCESSFULLY_DELETED', $nb );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'JERROR_AN_ERROR_HAS_OCCURRED' );
			$msgType	=	'error';
		}
		
		$this->setRedirect( $this->_getReturnPage(), $msg, $msgType );
	}
	
	// download
	public function download()
	{
		$app		=	JFactory::getApplication();
		$id			=	$app->input->getInt( 'id', 0 );
		$fieldname	=	$app->input->getString( 'file', '' );
		$collection	=	$app->input->getString( 'collection', '' );
		$xi			=	$app->input->getString( 'xi', 0 );
		$client		=	$app->input->getString( 'client', 'content' );
		$user		=	JFactory::getUser();
		
		if ( ! $id ) {
			$file	=	$fieldname;
		} else {
			$field	=	JCckDatabase::loadObject( 'SELECT a.* FROM #__cck_core_fields AS a WHERE a.name="'.( ( $collection != '' ) ? $collection : $fieldname ).'"' ); //#
			$query	=	'SELECT a.pk, a.author_id, a.cck as type, b.'.$field->storage_field.' as value FROM #__cck_core AS a LEFT JOIN '.$field->storage_table.' AS b on b.id = a.pk WHERE a.id ='.(int)$id;
			$core	=	JCckDatabase::loadObject( $query );
			switch ( $field->storage ) { //todo: call plugins!
				case 'custom':
					if ( $collection != '' ) {
						$regex	=	CCK_Content::getRegex_Group( $fieldname, $collection, $xi );
						preg_match( $regex, $core->value, $matches );
						$value	=	$matches[1];
					} else {
						$regex	=	CCK_Content::getRegex_Field( $fieldname );
						preg_match( $regex, $core->value, $matches );
						$value	=	$matches[1];
					}
					break;
				case 'standard':
				default:
					$value	=	$core->value;
					break;
			}
			
			// Access
			// $current	=	JSite::getMenu()->getActive()->id;
			$clients	=	JCckDatabase::loadObjectList( 'SELECT a.fieldid, a.client, a.access FROM #__cck_core_type_field AS a LEFT JOIN #__cck_core_types AS b ON b.id = a.typeid'
														. ' WHERE a.fieldid = '.(int)$field->id.' AND b.name="'.(string)$core->type.'"', 'client' );
			$access		=	( isset( $clients[$client]->access ) ) ? (int)$clients[$client]->access : 0;
			$autorised	=	$user->getAuthorisedViewLevels();
			if ( !( $access > 0 && array_search( $access, $autorised ) !== false ) ) {
				$this->setRedirect( 'index.php', JText::_( 'COM_CCK_ALERT_FILE_NOT_AUTH' ), "error" );
				return;
			}
			JPluginHelper::importPlugin( 'cck_field' );
			$dispatcher	=	JDispatcher::getInstance();
			$config		=	array( 'client'=>$client, 'id'=>id, 'pk'=>$core->pk, 'pkb'=>0, 'task'=>'download' );
			$field		=	JCckDatabase::loadObject( 'SELECT a.* FROM #__cck_core_fields AS a WHERE a.name="'.$fieldname.'"' ); //#
			$dispatcher->trigger( 'onCCK_FieldPrepareContent', array( &$field, $value, &$config ) );

			// Path Folder
			if ( $collection != '' ) {
				$group_x	=	JCckDatabase::loadObject( 'SELECT a.options2 FROM #__cck_core_fields AS a WHERE a.name="'.$fieldname.'"' );
				$f_opt2		=	JCckDev::fromJSON( $group_x->options2 );
			} else {
				$f_opt2		=	JCckDev::fromJSON( $field->options2 );
			}
			$file	=	'';
			if ( isset( $f_opt2['storage_format'] ) && $f_opt2['storage_format'] ) {
				$file	.=	$f_opt2['path'];
				$file	.=	( isset( $f_opt2['path_user'] ) && $f_opt2['path_user'] ) ? $core->author_id.'/' : '';
				$file	.=	( isset( $f_opt2['path_content'] ) && $f_opt2['path_content'] ) ? $core->pk.'/' : '';
			}
			$file	.=	$field->value;
		}
		
		$path	=	JPATH_ROOT.'/'.$file;
		if ( is_file( $path ) && $file ) {
			$size	=	filesize( $path ); 
			$ext	=	strtolower( substr ( strrchr( $path, '.' ) , 1 ) );
			if ( $ext == 'php' ) {
				return;
			}
			$name	=	substr( $path, strrpos( $path, '/' ) + 1, strrpos( $path, '.' ) );
			if ( $path ) {
				$task2	=	isset( $field->task ) ? $field->task : 'download';
				if ( $task2 == 'read' ) {
					$this->setRedirect( JURI::root( true ).'/'.$file );
				} else {
					if ( $id ) {
						$this->_download_hits( $id, $fieldname, $collection, $xi );
					}
					set_time_limit( 0 );
					@ob_end_clean();
					include JPATH_ROOT.'/components/com_cck/download.php';
				}
			}
		} else {
			$this->setRedirect( 'index.php', JText::_( 'COM_CCK_ALERT_FILE_DOESNT_EXIST' ), 'error' );
		}
	}
	
	// getRoute
	public function getRoute()
	{
		$app		=	JFactory::getApplication();
		$location	=	$app->input->get( 'location', 'joomla_article' );
		$type		=	$app->input->get( 'type', '' );
		$pk			=	$app->input->getInt( 'pk', 0 );
		$itemId		=	$app->input->getInt( 'Itemid', 0 );
		$sef		=	0;
		
		if ( !$pk ) {
			return 'index.php';
		}
		
		if ( $itemId > 0 ) {
			$target	=	JCckDatabase::loadResult( 'SELECT link FROM #__menu WHERE id = '.(int)$itemId );
			if ( $target ) {
				$vars	=	explode( '&', $target );
				foreach ( $vars as $var ) {
					$v	=	explode( '=', $var );
					if ( $v[0] == 'search' ) {
						$target	=	$v[1];
						break;
					}
				}
				$vars	=	JCckDatabase::loadResult( 'SELECT options FROM #__cck_core_searchs WHERE name = "'.(string)$target.'"' );
				if ( $vars ) {
					$vars	=	new JRegistry( $vars );
					$sef	=	$vars->get( 'sef', JCck::getConfig_Param( 'sef', '2' ) );
				}
			}
		}
		
		require_once JPATH_SITE.'/plugins/cck_storage_location/'.$location.'/'.$location.'.php';
		echo JCck::callFunc_Array( 'plgCCK_Storage_Location'.$location, 'getRoute', array( $pk, $sef, $itemId, array( 'type'=>$type ) ) );
	}

	// save	
	public function save()
	{
		JSession::checkToken() or jexit( JText::_( 'JINVALID_TOKEN' ) );
		
		$app		=	JFactory::getApplication();
		$model		=	$this->getModel( 'form' );
		$preconfig	=	$app->input->post->get( 'config', array(), 'array' );
		$task		=	$this->getTask();
		
		$config		=	$model->store( $preconfig );
		$id			=	$config['pk'];
		$itemId		=	$preconfig['itemId'];
		
		if ( $config['validate'] == 'retry' ) {
			if ( $app->input->get( 'option', '' ) == 'com_cck' ) {
				$view	=	$app->input->get( 'view', '' );
				if ( $view == 'list' ) {
					$app->input->set( 'task', 'search' );
					$app->input->set( 'retry', $config['type'] );
					parent::display();
					return false;
				} elseif ( $view == 'form' ) {
					$app->input->set( 'retry', $config['type'] );
					parent::display();
					return false;
				}
			}
		}
		
		if ( $id ) {
			if ( $config['message_style'] ) {
				if ( isset( $config['message'] ) ) {
					$msg	=	( $config['doTranslation'] ) ? JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $config['message'] ) ) ) : $config['message'];
				} else {
					$msg	=	JText::_( 'COM_CCK_SUCCESSFULLY_SAVED' );
				}
				$msgType	=	$config['message_style'];
			} else {
				$msg		=	'';
				$msgType	=	'';
			}
			if ( $config['stage'] > -1 ) {
				if ( $config['url'] ) {
					$link	=	$config['url'];
				} elseif ( !( isset( $preconfig['skip'] ) && $preconfig['skip'] == '1' ) ) {
					$link	=	'index.php?option=com_cck&view=form&layout=edit&type='.$config['type'].'&id='.$id;
					if ( $itemId > 0 ) {
						$link	.=	'&Itemid='.$itemId;
					}
					if ( $config['stage'] > 0 ) {
						$link	.=	'&stage='.$config['stage'];
					}
					$link	=	JRoute::_( $link );
				}
				if ( $link != '' ) {
					$this->setRedirect( htmlspecialchars_decode( $link ), $msg, $msgType );
					return;
				}
			}
		} else {
			$msg		=	JText::_( 'JERROR_AN_ERROR_HAS_OCCURRED' );
			$msgType	= 'error';
		}
		
		$redirect	=	$config['options']['redirection'];
		$link		=	$this->_getReturnPage( false );
		if ( !$link ) {
			switch ( $redirect ) {
				case 'content':
					$loc		=	JCckDatabase::loadResult( 'SELECT storage_location FROM #__cck_core WHERE id = '.(int)$config['id'] );
					$sef		=	0;
					$itemId2	=	isset( $config['options']['redirection_itemid'] ) ? (int)$config['options']['redirection_itemid'] : 0;
					if ( $itemId2 > 0 ) {
						$target	=	JCckDatabase::loadResult( 'SELECT link FROM #__menu WHERE id = '.(int)$itemId2 );
						if ( $target ) {
							$vars	=	explode( '&', $target );
							foreach ( $vars as $var ) {
								$v	=	explode( '=', $var );
								if ( $v[0] == 'search' ) {
									$target	=	$v[1];
									break;
								}
							}
							$vars	=	JCckDatabase::loadResult( 'SELECT options FROM #__cck_core_searchs WHERE name = "'.(string)$target.'"' );
							if ( $vars ) {
								$vars	=	new JRegistry( $vars );
								$sef	=	$vars->get( 'sef', JCck::getConfig_Param( 'sef', '2' ) );
							}
						}
					}
					if ( $loc ) {
						require_once JPATH_SITE.'/plugins/cck_storage_location/'.$loc.'/'.$loc.'.php';
						$link	=	JCck::callFunc_Array( 'plgCCK_Storage_Location'.$loc, 'getRoute', array( $config['pk'], $sef, $itemId2, array( 'type'=>$config['type'] ) ) );
					} else {
						$link	=	'index.php';
					}
					break;
				case 'form':
					$link	=	'index.php?option=com_cck&view=form&layout=edit&type='.$config['type'];
					if ( $itemId > 0 ) {
						$link	.=	'&Itemid='.$itemId;
					}
					$link	=	JRoute::_( $link );
					break;
				case 'form_edition':
					$link	=	'index.php?option=com_cck&view=form&layout=edit&type='.$config['type'].'&id='.$id;
					if ( $itemId > 0 ) {
						$link	.=	'&Itemid='.$itemId;
					}
					$link	=	JRoute::_( $link );
					break;
				case 'url':
					$link	=	JRoute::_( $config['options']['redirection_url'] );
					break;
				default:
					$link	=	( $config['url'] ) ? $config['url'] : 'index.php';
					break;
			}
		}
		if ( $id ) {
			$char	=	( strpos( $link, '?' ) > 0 ) ? '&' : '?' ;			
			if ( isset( $config['thanks'] ) ) {
				$thanks			=	( @$config['thanks']->name ) ? $config['thanks']->name : 'thanks';
				$thanks_value	=	( @$config['thanks']->value ) ? $config['thanks']->value : $preconfig['type'];
				$link			.=	$char.$thanks.'='.$thanks_value;
			} else {
				$link			.=	$char.'thanks='.$preconfig['type'];
			}
		}
		$this->setRedirect( htmlspecialchars_decode( $link ), $msg, $msgType );
	}
	
	// search	
	public function search()
	{
		parent::display( true );
	}
	
	// _download_hits
	protected function _download_hits( $id, $fieldname, $collection = '', $x = 0 )
	{
		$where	=	'a.id = '.(int)$id.' AND a.field = "'.(string)$fieldname.'" AND a.collection = "'.(string)$collection.'" AND a.x = '.(int)$x;
		$hits	=	JCckDatabase::loadResult( 'SELECT a.hits FROM #__cck_core_downloads AS a WHERE '.$where );
		
		if ( !$hits ) {
			JCckDatabase::execute( 'INSERT INTO #__cck_core_downloads(`id`, `field`, `collection`, `x`, `hits`) VALUES('.(int)$id.', "'.(string)$fieldname.'", "'.(string)$collection.'", '.(int)$x.', 1)' );
		} else {
			$hits++;
			JCckDatabase::execute( 'UPDATE #__cck_core_downloads AS a SET a.hits = '.(int)$hits.' WHERE '.$where.' AND a.id = '.(int)$id );
		}
		
		return $hits;
	}
	
	// _getReturnPage
	protected function _getReturnPage( $base = true )
	{
		$app	=	JFactory::getApplication();
		$return	=	$app->input->getBase64( 'return' );
		
		if ( empty( $return ) || !JUri::isInternal( urldecode( base64_decode( $return ) ) ) ) {
			return ( $base == true ) ? JURI::base() : '';
		} else {
			return urldecode( base64_decode( $return ) );
		}
	}
}
?>