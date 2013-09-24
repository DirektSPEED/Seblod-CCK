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

// -- Initialize
require_once dirname(__FILE__).'/config.php';
$cck	=	CCK_Rendering::getInstance( $this->template );
if ( $cck->initialize() === false ) { return; }

$class_table	=	trim( $cck->getStyleParam( 'class_table', 'category zebra table' ) );
$class_table	=	$class_table ? ' class="'.$class_table.'"' : '';
$class_row0		=	trim( $cck->getStyleParam( 'class_table_tr_even', 'cat-list-row%i' ) );
$class_row0		=	$class_row0 ? ' class="'.str_replace( '%i', '0', $class_row0 ).'"' : '';
$class_row1		=	trim( $cck->getStyleParam( 'class_table_tr_odd', 'cat-list-row%i' ) );
$class_row1		=	$class_row1 ? ' class="'.str_replace( '%i', '1', $class_row1 ).'"' : '';

$doc	=	JFactory::getDocument();
$doc->addStyleSheet( JURI::root( true ).'/templates/'.$cck->template. '/css/'.'base.css' );

// -- Render
?>
<div id="<?php echo $cck->id; ?>" class="<?php echo $cck->id_class; ?>cck-f100 cck-pad-<?php echo $cck->getStyleParam( 'position_margin', '10' ); ?>">
    <div>
    <?php
	$css		=	array();
	$items		=	$cck->getItems();
	$positions	=	$cck->getPositions();
	if ( count( $items ) ) { ?>
		<table<?php echo $class_table; ?>>
			<?php
			$head	=	'';
			$thead	=	false;
			foreach ( $positions as $name=>$position ) {
				$class		=	$position->css;
				$css[$name]	=	$class ? ' class="'.$class.'"' : '';
				$legend		=	( $position->legend ) ? $position->legend : ( ( $position->legend2 ) ? $position->legend2 : '' );
				$width		=	$cck->w( $name );
				$width		=	( $width ) ? ' width="'.$width.'"' : '';
				if ( $legend || $width ) {
					if ( $legend ) {
						$thead	=	true;
					}
					$head		.=	'<th'.$css[$name].$width.'>'.$legend.'</th>';
				}
			}
            ?>
            <?php if ( $head && $thead ) { ?>
            <thead>
                <tr><?php echo $head; ?></tr>
			</thead>
			<?php } ?>
            <tbody>
            <?php
			$i	=	0;
            foreach ( $items as $item ) {
				?>
                <tr <?php echo ${'class_row'.($i % 2)}; ?>>
				<?php
                foreach ( $positions as $name=>$position ) {
                    $fieldnames	=	$cck->getFields( $name, '', false );
                    echo '<td'.$css[$name].'>';
                    foreach ( $fieldnames as $fieldname ) {
						$html	=	$item->renderField( $fieldname );
						if ( $html != '' ) {
	                        echo '<div style="clear:left;">'.$html.'</div>';	//todo: markup
						}
                    }
                    echo '</td>';
                }
                ?>
                </tr>
            <?php $i++; } ?>
			</tbody>
		</table>
	<?php } ?>
    </div>
</div>
<?php
// -- Finalize
$cck->finalize();
?>