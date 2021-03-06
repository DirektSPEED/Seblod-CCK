<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: default.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

$css		=	array();
$doc		=	JFactory::getDocument();
$user		=	JFactory::getUser();
$userId		=	$user->id;
$listOrder	=	$this->state->get( 'list.ordering' );
$listDir	=	$this->state->get( 'list.direction' );
$title2		=	JText::_( 'COM_CCK_VIEW_THIS_SITE' );

$config		=	JCckDev::init( array( '42', 'button_submit', 'select_simple', 'text' ), true, array( 'vName'=>$this->vName ) );
$cck		=	JCckDev::preload( array( 'core_filter_input', 'core_filter_go', 'core_filter_search', 'core_filter_clear', 'core_location_filter',
										 'core_state_filter' ) );
JText::script( 'COM_CCK_CONFIRM_DELETE' );
Helper_Include::addDependencies( $this->getName(), $this->getLayout() );
?>

<form action="<?php echo JRoute::_( 'index.php?option='.$this->option.'&view='.$this->getName() ); ?>" method="post" id="adminForm" name="adminForm">
<?php if ( !empty( $this->sidebar ) ) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php } else { ?>
	<div id="j-main-container">
<?php } ?>

<?php include_once dirname(__FILE__).'/default_filter.php'; ?>
<div class="<?php echo $this->css['items']; ?>">
	<table class="<?php echo $this->css['table']; ?>">
	<thead>
		<tr class="half">
			<th width="32" class="center hidden-phone nowrap" rowspan="2"><?php Helper_Display::quickSlideTo( 'pagination-bottom', 'down' ); ?></th>
			<th width="30" class="center hidden-phone" rowspan="2">
            	<input type="checkbox" name="toggle" value="" title="<?php echo JText::_( 'JGLOBAL_CHECK_ALL' ); ?>" onclick="Joomla.checkAll(this);" />
			</th>
			<th class="center" rowspan="2" colspan="2"><?php echo JHtml::_( 'grid.sort', 'COM_CCK_TITLE', 'a.title', $listDir, $listOrder ); ?></th>
			<th width="20%" class="center hidden-phone nowrap" rowspan="2"><?php echo JHtml::_( 'grid.sort', 'COM_CCK_URL', 'a.name', $listDir, $listOrder ); ?></th>
            <th width="30%" class="center hidden-phone nowrap" colspan="3"><?php echo JText::_( 'COM_CCK_STATISTICS' ); ?></th>
			<th width="10%" class="center nowrap" rowspan="2"><?php echo JHtml::_( 'grid.sort', 'COM_CCK_STATUS', 'a.published', $listDir, $listOrder ); ?></th>
			<th width="32" class="center hidden-phone nowrap" rowspan="2"><?php echo JHtml::_( 'grid.sort', 'COM_CCK_ID', 'a.id', $listDir, $listOrder ); ?></th>
		</tr>
		<tr class="half">
			<th width="10%" class="center hidden-phone nowrap"><?php echo '#&nbsp;' . JText::_( 'COM_CCK_ARTICLES' ); ?></th>
			<th width="10%" class="center hidden-phone nowrap"><?php echo '#&nbsp;' . JText::_( 'COM_CCK_USERS' ); ?></th>
			<th width="10%" class="center hidden-phone nowrap"><?php echo '#&nbsp;' . JText::_( 'COM_CCK_VISITS' ); ?></th>
		</tr>
	</thead>
    <tbody>
	<?php
	foreach ( $this->items as $i=>$item ) {
		$checkedOut		= 	! ( $item->checked_out == $userId || $item->checked_out == 0 );
		$canCheckin		=	$user->authorise( 'core.manage', 'com_checkin' ) || $item->checked_out == $userId || $item->checked_out == 0;
		$canChange		=	$user->authorise( 'core.edit.state', CCK_COM ) && $canCheckin;
		$canEdit		=	$user->authorise( 'core.edit', CCK_COM );
		$canEditOwn		=	'';	
		
		$link 			=	JRoute::_( 'index.php?option='.$this->option.'&task='.$this->vName.'.edit&id='. $item->id );
		$link2			=	'http://'.$item->name;
		?>
		<tr class="row<?php echo $i % 2; ?>" height="64px;">
			<td class="center hidden-phone"><?php Helper_Display::quickSlideTo( 'pagination-bottom', $i + 1 ); ?></td>
			<td class="center hidden-phone"><?php echo JHtml::_( 'grid.id', $i, $item->id ); ?></td>
			<td width="30px" class="center"><a target="_blank" href="<?php echo $link2; ?>"><img src="components/<?php echo CCK_COM; ?>/assets/images/24/icon-24-sites.png" title="<?php echo $title2 ?>" /></a></td>
			<td>
				<div class="title-left" id="title-<?php echo $item->id; ?>">
					<?php
					if ( $item->checked_out ) {
						echo JHtml::_( 'jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->vName.'s.', $canCheckin )."\n";
					}
					if ( $canEdit && ! $checkedOut ) {
						echo '<a href="'.$link.'">'.$this->escape( $item->title ).'</a>';
					} else {
						echo '<span>'.$this->escape( $item->title ).'</span>';
					}
					echo '<div class="small visible-phone">'.$item->name.'</div>'
					?>
				</div>
			</td>
			<td class="center hidden-phone small"><?php echo $item->name; ?></td>
			<td class="center hidden-phone"><span><?php echo $item->articles; ?></span></td>
			<td class="center hidden-phone"><span><?php echo $item->users; ?></span></td>
            <td class="center hidden-phone">-</td>
			<td class="center"><?php echo JHtml::_( 'jgrid.published', $item->published, $i, $this->vName.'s.', $canChange, 'cb' ); ?></td>
			<td class="center hidden-phone"><?php Helper_Display::quickSlideTo( 'border-top', $item->id ); ?></td>
		</tr>
		<?php
	}
	?>
    </tbody>
	<tfoot>
		<tr height="40px;">
	        <td class="center hidden-phone"><?php Helper_Display::quickSlideTo( 'border-top', 'up' ); ?></td>
			<td class="center" colspan="8" id="pagination-bottom"><?php echo $this->pagination->getListFooter(); ?></td>
			<td class="center hidden-phone"><?php Helper_Display::quickSlideTo( 'border-top', 'up' ); ?></td>
		</tr>
	</tfoot>
	</table>
</div>
<?php include_once dirname(__FILE__).'/default_batch.php'; ?>
<div class="clr"></div>
<div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="return_v" id="return_v" value="sites" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDir; ?>" />
	<?php echo JHtml::_( 'form.token' ); ?>
</div>

<?php
Helper_Include::addStyleDeclaration( implode( '', $css ) );
Helper_Display::quickCopyright();
?>
</div>
</form>

<script type="text/javascript">
(function ($){
	Joomla.submitbutton = function(task, cid) {
		if (task == "<?php echo $this->vName.'s'; ?>.delete") {
			if (confirm(Joomla.JText._('COM_CCK_CONFIRM_DELETE'))) {
				Joomla.submitform(task);
			} else {
				return false;
			}
		}
		Joomla.submitform(task);
	}
})(jQuery);
</script>