<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: default_batch.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

if ( JCck::on() ) { ?>
    <div class="<?php echo $this->css['batch']; ?>" id="collapseModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3><?php echo JText::_( 'COM_CCK_BATCH_PROCESS'); ?></h3>
        </div>
        <div class="modal-body">
            <p><?php echo JText::_( 'COM_CCK_BATCH_PROCESS_'.$this->vName ); ?></p>
            <div class="control-group">
                <div class="control-label">
                    <label for="batch_folder"><?php echo JText::_( 'COM_CCK_SET_APP_FOLDER' ); ?></label>
                </div>
                <div class="controls">
                    <?php echo JCckDev::getForm( $cck['core_folder'], '', $config, array( 'label'=>_C0_TEXT, 'storage_field'=>'batch_folder', 'css'=>'no-chosen' ) ); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" onclick="" data-dismiss="modal"><?php echo JText::_( 'JCANCEL' ); ?></button>
            <button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('batch_folder');"><?php echo JText::_( 'COM_CCK_GO' ); ?></button>
        </div>
	</div>
<?php } else { ?>
    <div class="<?php echo $this->css['batch']; ?>" id="collapseModal">
        <div class="legend top left"><?php echo JText::_( 'COM_CCK_BATCH_PROCESS' ); ?></div>
        <ul class="adminformlist">
            <li>
                <?php 
                echo JCckDev::renderForm( $cck['core_folder'], '', $config, array( 'label'=>_C0_TEXT, 'storage_field'=>'batch_folder' ) );
                ?>
                <button class="inputbutton" type="submit" onclick="Joomla.submitbutton('batch_folder');"><?php echo JText::_( 'COM_CCK_GO' ); ?></button>
            </li>
        </ul>
    </div>
<?php } ?>