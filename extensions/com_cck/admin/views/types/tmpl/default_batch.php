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
        <div class="modal-body">
            <p><?php echo JText::_( 'COM_CCK_BATCH_PROCESS_'.$this->vName.'_2' ); ?></p>
            <div class="control-group">
                <div class="control-label">
                    <label for="duplicate_title"><?php echo JText::_( 'COM_CCK_CHOOSE_A_TITLE' ); ?></label>
                </div>
                <div class="controls">
                    <?php echo JCckDev::getForm( $cck['core_dev_text'], '', $config, array( 'label'=>'Title', 'storage_field'=>'duplicate_title' ) ); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" onclick="" data-dismiss="modal"><?php echo JText::_( 'JCANCEL' ); ?></button>
            <button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('types.duplicate');"><?php echo JText::_( 'COM_CCK_GO' ); ?></button>
        </div>
    </div>
    <?php if ( 1 == 1 ) {
        $items  =   JCckDatabase::loadObjectList( 'SELECT id, title, name, app, icon_path as icon FROM #__cck_core_folders WHERE featured = 1 ORDER BY lft' );
    ?>
    <div class="<?php echo $this->css['batch']; ?>" id="collapseModal2">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3><?php echo JText::_( 'JTOOLBAR_NEW' ).' '.JText::_( 'COM_CCK_'._C2_TEXT ); ?></h3>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <div class="control-label">
                    <label><?php echo JText::_( 'COM_CCK_SELECT_WHICH_OBJECT_TYPE' ); ?></label>
                </div>
            </div>
            <div class="cpanel row-fluid">
                <?php
                if ( count( $items ) ) {
                    foreach ( $items as $item ) {
                        $image  =   ( $item->icon ) ? $item->icon : 'administrator/components/com_cck/assets/images/48/icon-48-form.png';
                        $text   =   $item->title;
                    ?>
                    <div class="wrapper-icon span3">
                        <div class="icon">
                            <a href="javascript:void(0);" onclick="JCck.Dev.addNew('<?php echo $item->id; ?>');">
                                <?php echo JHtml::_( 'image', $image, htmlspecialchars( str_replace( '<br />', ' ', $text ) ) ); ?>
                                <span><?php echo $text; ?></span>
                            </a>
                        </div>
                    </div>
                <?php } } ?>
                <div class="cpanel">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" onclick="" data-dismiss="modal"><?php echo JText::_( 'JCANCEL' ); ?></button>
            <button class="btn btn-primary" type="button" onclick="JCck.Dev.addNew();"><?php echo JText::_( 'COM_CCK_CREATE_BLANK' ); ?></button>
        </div>
    </div>
    <?php } ?>
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
    <div class="<?php echo $this->css['batch']; ?>">
        <div class="legend top left"><?php echo JText::_( 'COM_CCK_DUPLICATE_PROCESS' ); ?></div>
        <ul class="adminformlist">
            <li>
                <?php
                echo JCckDev::renderForm( $cck['core_dev_text'], '', $config, array( 'label'=>'Title', 'storage_field'=>'duplicate_title' ) );
                ?>
                <button class="inputbutton" type="submit" onclick="Joomla.submitbutton('types.duplicate');"><?php echo JText::_( 'JTOOLBAR_DUPLICATE' ); ?></button>
            </li>
        </ul>
    </div>
<?php } ?>