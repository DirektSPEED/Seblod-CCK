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

JCckDev::forceStorage();
$class		=	( JCck::on() ) ? '' : ' class="hide"';
$options2	=	JCckDev::fromJSON( $this->item->options2 );
?>

<div class="seblod">
    <?php echo JCckDev::renderLegend( JText::_( 'COM_CCK_CONSTRUCTION' ), JText::_( 'PLG_CCK_FIELD_'.$this->item->type.'_DESC' ) ); ?>
    <ul class="adminformlist adminformlist-2cols">
        <?php
		echo JCckDev::renderForm( 'core_label', $this->item->label, $config );
		echo '<li'.$class.'><label>'.JText::_( 'COM_CCK_LABEL_ICON' ).'</label>'
		 .	 JCckDev::getForm( 'core_dev_select', $this->item->bool6, $config, array( 'label'=>'Label Icon', 'defaultvalue'=>'0', 'selectlabel'=>'',
		 																			  'options'=>'Hide=0||Show=optgroup||Append=1||Prepend=2||Replace=3', 'storage_field'=>'bool6' ) )
		 .	 JCckDev::getForm( 'core_icons', @$options2['icon'], $config, array( 'css'=>'max-width-150' ) )
		 .	 '</li>';
		echo JCckDev::renderForm( 'core_bool', $this->item->bool, $config, array( 'label'=>'TYPE', 'defaultvalue'=>'0', 'options'=>'Input=0||Button=1' ) );
		echo JCckDev::renderForm( 'core_bool2', $this->item->bool2, $config, array( 'label'=>'SHOW_ALTERNATIVE_LINK', 'defaultvalue'=>'1', 'options'=>'Hide=0||Show=optgroup||Use Default=1||Custom=2' ) );
		echo JCckDev::renderForm( 'core_bool3', $this->item->bool3, $config, array( 'label'=>'Show Alternative Or', 'defaultvalue'=>'1', 'options'=>'Hide=0||Show=1' ) );
		echo JCckDev::renderBlank( '<input type="hidden" id="blank_li" value="" />' );
		echo '<li><label>'.JText::_( 'COM_CCK_TEXT_LINK' ).'</label>'
		 .	 JCckDev::getForm( 'core_dev_text', @$options2['alt_link_text'], $config, array( 'label'=>'Text', 'required'=>'required', 'size'=>14, 'storage_field'=>'json[options2][alt_link_text]' ) )
		 .	 JCckDev::getForm( 'core_plugins', @$options2['alt_link'], $config, array( 'selectlabel'=>'Select', 'location'=>'field_link', 'required'=>'required',
																					   'storage_field'=>'json[options2][alt_link]', 'attributes'=>'style="max-width:98px"' ) )
		 .	 '<input type="hidden" id="json_options2_alt_link_options" name="json[options2][alt_link_options]" value="'.htmlspecialchars( @$options2['alt_link_options'] ).'" />'
		 .	 '<span class="c_link" id="json_options2_alt" name="json_options2_alt">+</span>'
		 .	 '</li>';
		
		echo JCckDev::renderSpacer( JText::_( 'COM_CCK_STORAGE' ), JText::_( 'COM_CCK_STORAGE_DESC' ) );
		echo JCckDev::getForm( 'core_storage', $this->item->storage, $config );
        ?>
    </ul>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#json_options2_icon').isVisibleWhen('bool6','1,2,3',false);
	$('#bool3').isVisibleWhen('bool2','1,2');
	$('#json_options2_alt_link_text, #blank_li').isVisibleWhen('bool2','2');
	$("div#layer").on("click", "span.c_link", function() {
		var type = $("#json_options2_alt_link").val();
		if (type) {
			var field = $(this).attr("name");
			var url = "index.php?option=com_cck&task=box.add&tmpl=component&file=plugins/cck_field_link/"+type+"/tmpl/edit.php&id="+field+"&name="+type+"&validation=1";
			$.fn.colorbox({href:url, iframe:true, innerWidth:930, innerHeight:550, overlayClose:false, fixed:true, onLoad: function(){ $('#cboxClose').remove();}});
		}
	});
});
</script>