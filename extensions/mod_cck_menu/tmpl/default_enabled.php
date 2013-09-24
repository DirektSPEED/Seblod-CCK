<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: default_enabled.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// Root
$empty	=	true;
if ( $root ) {
	$menu->addChild( new JMenuNode( $root, '#' ), true );
}

// Base
if ( $mode == 1 || $mode == 2 ) {
	$uix	=	JCck::getUIX();
	if ( $uix == 'compact' ) {
		if ( $mode == 2 && JFactory::getUser()->authorise( 'core.manage', 'com_cck' ) ) {
			$empty	=	 false;
			$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_APP_FOLDER_MANAGER' ), 'index.php?option=com_cck&view=folders' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=folders&task=folder.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( '-&nbsp;'.JText::_( 'MOD_CCK_MENU_FORM_MANAGER' ), 'index.php?option=com_cck&view=types' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=types&task=type.add' ) );
			}
			$menu->getParent();
		}
	} else {
		if ( $mode == 2 && JFactory::getUser()->authorise( 'core.manage', 'com_cck' ) ) {
			$empty	=	 false;
			$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_APP_FOLDER_MANAGER' ), 'index.php?option=com_cck&view=folders', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=folders&task=folder.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( '-&nbsp;'.JText::_( 'MOD_CCK_MENU_CONTENT_TYPE_MANAGER' ), 'index.php?option=com_cck&view=types', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=types&task=type.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( '-&nbsp;'.JText::_( 'MOD_CCK_MENU_FIELD_MANAGER' ), 'index.php?option=com_cck&view=fields', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=fields&task=field.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( '-&nbsp;'.JText::_( 'MOD_CCK_MENU_SEARCH_TYPE_MANAGER' ), 'index.php?option=com_cck&view=searchs', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=searchs&task=search.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( '-&nbsp;'.JText::_( 'MOD_CCK_MENU_TEMPLATE_MANAGER' ), 'index.php?option=com_cck&view=templates', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=templates&task=template.add' ) );
			}
			$menu->getParent();
			$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_SITE_MANAGER' ), 'index.php?option=com_cck&view=sites', 'cck' ), true );
			if ( $options['new'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_NEW' ), 'index.php?option=com_cck&view=folders&task=site.add' ) );
			}
			$menu->getParent();
		}
	}
	if ( !$empty ) {
		$menu->addSeparator();
	}
	$empty	=	 false;
	$menu->addChild( new JMenuNode( 'SEBLOD.com', 'http://www.seblod.com/', 'cck', false, '_blank' ), true );
	$menu->addChild( new JMenuNode( 'Community', 'http://www.seblod.com/community', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Blog', 'http://www.seblod.com/community/blog', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Events', 'http://www.seblod.com/community/events', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Forums', 'http://www.seblod.com/community/forums', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Showcase', 'http://www.seblod.com/community/showcase', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Social Hub', 'http://www.seblod.com/community/social-hub', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Users', 'http://www.seblod.com/community/users', '', false, '_blank' ) );
	$menu->addSeparator();
	$menu->addChild( new JMenuNode( 'Products', 'http://www.seblod.com/products', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Liked', 'http://www.seblod.com/products/liked', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- New', 'http://www.seblod.com/products/new', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Popular', 'http://www.seblod.com/products/popular', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Top', 'http://www.seblod.com/products/top', '', false, '_blank' ) );
	$menu->addSeparator();
	$menu->addChild( new JMenuNode( 'Resources', 'http://www.seblod.com/resources', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Faq', 'http://www.seblod.com/resources/faq', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Manuals', 'http://www.seblod.com/resources/manuals', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Marketing', 'http://www.seblod.com/resources/marketing', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Tracker', 'http://www.seblod.com/resources/tracker', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Tutorials', 'http://www.seblod.com/resources/tutorials', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Videos', 'http://www.seblod.com/resources/videos', '', false, '_blank' ) );
	$menu->addSeparator();
	$menu->addChild( new JMenuNode( 'Services', 'http://www.seblod.com/services', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Live Support', 'http://www.seblod.com/services/live-support', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Ticket Support', 'http://www.seblod.com/services/ticket-support', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Webinars', 'http://www.seblod.com/services/webinars', '', false, '_blank' ) );
	$menu->addChild( new JMenuNode( '- Worldwide', 'http://www.seblod.com/services/worldwide', '', false, '_blank' ) );
	$menu->getParent();
} elseif ( $mode == 3 ) {
	if ( $user->authorise( 'core.manage', 'com_cck_ecommerce' ) ) {
		$empty				=	 false;
		$uix_ecommerce		=	JCckEcommerce::getUIX();
		$product_manager	=	JComponentHelper::getParams( 'com_cck_ecommerce' )->get( 'product_manager_link' );
		$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_CART_MANAGER' ), 'index.php?option=com_cck_ecommerce&view=carts' ) );
		if ( $uix_ecommerce == 'full' ) {
			$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_ORDER_MANAGER' ), 'index.php?option=com_cck_ecommerce&view=orders' ) );
			if ( $options['ecommerce'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_PAYMENT_MANAGER' ), 'index.php?option=com_cck_ecommerce&view=payments' ) );
			}
			if ( $product_manager ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_PRODUCT_MANAGER' ), $product_manager ) );
			}
			if ( $options['ecommerce'] ) {
				$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_SHIPPING_MANAGER' ), 'index.php?option=com_cck_ecommerce&view=shippings' ) );
			}
			$menu->addChild( new JMenuNode( JText::_( 'MOD_CCK_MENU_STORE_MANAGER' ), 'index.php?option=com_cck_ecommerce&view=stores' ) );
		}
	}
} elseif ( $mode == 4 ) {
	$items	=	JCckDatabase::loadObjectList( 'SELECT a.id, a.name, a.title as text FROM #__cck_core_types AS a'
											. ' WHERE a.published = 1 AND a.location != "none" AND a.location != "site" AND a.storage_location != "none" ORDER BY text' );
	$link	=	'index.php?option=com_cck&view=form&type=';
	if ( count( $items ) ) {
		foreach ( $items as $item ) {
			if ( $user->authorise( 'core.create', 'com_cck.form.'.$item->id ) ) {
				$empty	=	 false;
				$text	=	JText::_( $item->text );
				$text	=	( strlen( $text ) > 30 ) ? substr( $text, 0, 30 ) . '..' : $text;
				$menu->addChild( new JMenuNode( $text, $link.$item->name ) );
			}
		}
	}
} elseif ( $mode == 5 ) {
	$groups	= implode( ',', $user->getAuthorisedViewLevels() );
	$items	=	JCckDatabase::loadObjectList( 'SELECT a.id, a.name, a.title as text FROM #__cck_core_searchs AS a'
											. ' WHERE a.published = 1 AND a.location != "none" AND a.location != "site" AND a.access IN ('.$groups.') ORDER BY text' );
	$link	=	'index.php?option=com_cck&view=list&search=';
	if ( count( $items ) ) {
		foreach ( $items as $item ) {
			$empty	=	 false;
			$text	=	JText::_( $item->text );
			$text	=	( strlen( $text ) > 30 ) ? substr( $text, 0, 30 ) . '..' : $text;
			$menu->addChild( new JMenuNode( $text, $link.$item->name ) );
		}
	}
	if ( $options['inline'] ) {
		$root	=	false;
	}
} elseif ( $mode == 6 ) {
	$items	=	JCckDatabase::loadObjectList( 'SELECT a.element, b.title as text FROM #__extensions AS a'
											. ' LEFT JOIN #__menu AS b on b.component_id = a.extension_id'
											. ' WHERE a.type = "component" AND a.element LIKE "com_cck\_%" ORDER BY title' );
	$link	=	'index.php?option=';
	if ( count( $items ) ) {
		foreach ($items as $item ) {
			if ( $user->authorise( 'core.manage', $item->element ) ) {
				$empty	=	 false;
				$menu->addChild( new JMenuNode( $item->text, $link.$item->element ) );
			}
		}
	}
}

// Custom
$items	=	modCckMenuHelper::getItems( $params );
if ( count( $items ) ) {
	if ( !$empty ) {
		$menu->addSeparator();
	}
	$empty	=	false;
	foreach ( $items as $key=>$item ) {
		$link	=	explode( '||', $item );
		$target	=	'';
		$text	=	( strpos( $link[0], 'icon-' ) !== false ) ? '<i class="'.$link[0].'"></i>' : JText::_( $link[0] );
		if ( $link[1] == 'root' ) {
			$link[1]	=	JUri::root();
			$target		=	'_blank';
		}
		$menu->addChild( new JMenuNode( $text, $link[1], '', false, $target ) );
	}
}
if ( !$empty && $root ) {
	$menu->getParent();
}
?>