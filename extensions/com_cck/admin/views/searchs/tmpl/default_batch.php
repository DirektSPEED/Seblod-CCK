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
            <button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('searchs.duplicate');"><?php echo JText::_( 'COM_CCK_GO' ); ?></button>
        </div>
    </div>
    <?php if ( 1 == 1 ) {
        $options        =   array();
        $options[]      =   JHtml::_( 'select.option', 0, '- '.JText::_( 'COM_CCK_NONE' ).' -', 'value', 'text' );
        $options2       =   JCckDatabase::loadObjectList( 'SELECT a.title AS text, a.name AS value FROM #__cck_core_types AS a WHERE a.published = 1 ORDER BY a.title' );
        if ( count( $options2 ) ) {
            $options    =   array_merge( $options, $options2 );
        }
        $select         =   JHtml::_( 'select.genericlist', $options, 'featured', 'class="inputbox no-chosen" size="1"', 'value', 'text', '', 'featured' );
        $options        =   JCckDatabase::loadObjectList( 'SELECT a.name AS text, a.name AS value FROM #__cck_core_templates AS a WHERE a.published = 1 AND a.mode = 2 ORDER BY a.title' );
        $select2        =   JHtml::_( 'select.genericlist', $options, 'template_search', 'class="inputbox no-chosen" size="1"', 'value', 'text', '', 'template_search' );
        $templates      =   JCckDatabase::loadObjectList( 'SELECT name, title FROM #__cck_core_templates WHERE mode = 2 AND published = 1 ORDER BY name', 'name' );
    ?>
    <div class="<?php echo $this->css['batch']; ?>" id="collapseModal2">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3><?php echo JText::_( 'JTOOLBAR_NEW' ).' '.JText::_( 'COM_CCK_'._C4_TEXT ); ?></h3>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <div class="control-label">
                    <label><?php echo JText::_( 'COM_CCK_SELECT_WHICH_CONTENT_TYPE' ); ?></label>
                </div>
                <div class="controls">
                    <?php echo $select; ?>
                </div>
            </div>
            <?php if ( count( $templates ) ) { ?>
            <div class="control-group">
                <div class="control-label">
                    <label><?php echo JText::_( 'COM_CCK_SELECT_WHICH_LIST_TEMPLATE' ); ?></label>
                </div>
                <div class="sly-wrapper">
                    <div class="scrollbar">
                        <div class="handle"></div>
                    </div>
                    <div class="sly">
                        <ul>
                            <li data-name="">None</li>
                            <?php
                            foreach ( $templates as $template ) {
                                echo '<li data-name="'.$template->name.'">'.$template->title.'</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <ul class="pages"></ul>
                </div>
                <input type="hidden" id="tpl_list" name="tpl_list" value="" />
            </div>
            <?php } ?>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" onclick="" data-dismiss="modal"><?php echo JText::_( 'JCANCEL' ); ?></button>
            <div class="btn-group dropup pull-right">
                <button class="btn btn-primary" type="button" onclick="JCck.Dev.addNew();"><?php echo JText::_( 'COM_CCK_CREATE' ); ?></button>
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0);" onclick="JCck.Dev.addNew('list');"><span class="icon-arrow-right"></span>
                    <?php echo JText::_( 'COM_CCK_CREATE_GO_TO_LIST' ); ?></a></li>
                    <!--<li><a href="javascript:void(0);" onclick="JCck.Dev.addNew('item');"><?php echo JText::_( 'COM_CCK_CREATE_GO_TO_ITEM' ); ?></a></li>-->
                </ul>
            </div>
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
                <button class="inputbutton" type="submit" onclick="Joomla.submitbutton('searchs.duplicate');"><?php echo JText::_( 'JTOOLBAR_DUPLICATE' ); ?></button>
            </li>
        </ul>
    </div>
<?php } ?>