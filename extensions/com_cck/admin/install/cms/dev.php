<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: dev.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// JCckDev
abstract class JCckDev
{
	// forceStorage
	public static function forceStorage( $value = 'none' )
	{
		$doc	=	JFactory::getDocument();
		
		if ( $value == 'none' ) {
			$js		=	'jQuery(document).ready(function($){ $("#storage").val( "'.$value.'" ).attr("disabled", "disabled"); $("#force_storage").val( "1" ); });';
		} else {
			$js		=	'jQuery(document).ready(function($){ if ( !$("#myid").val() ) { $("#storage").val( "'.$value.'" ); $("#force_storage").val( "1" ); } });';
		}
		
		echo '<script type="text/javascript">'.$js.'</script>';
	}
	
	// importPlugin
	public static function importPlugin( $type, $plugins )
	{
		if ( count( $plugins ) > 0 ) {
			foreach ( $plugins as $plugin ) {
				JPluginHelper::importPlugin( $type, $plugin );	// todo: improve
			}
		} else {
			JPluginHelper::importPlugin( $type );
		}
	}
	
	// init
	public static function init( $plugins = array(), $core = true, $more = array() )
	{
		self::importPlugin( 'cck_field', $plugins );
		
		$config	=	array( 'asset'=>'',
						   'asset_id'=>0,
						   'client'=>'',
						   'doTranslation'=>1,
						   'doValidation'=>0,
						   'fields'=>array(),
						   'item'=>'',
						   'validation'=>array()
						);
		
		if ( $core === true ) {
			JFactory::getLanguage()->load( 'plg_cck_field_validation_required', JPATH_ADMINISTRATOR, null, false, true );

			$config['doValidation']	=	2;
			require_once JPATH_PLUGINS.'/cck_field_validation/required/required.php';
		}
		$config['pk']				=	0;
		
		if ( count( $more ) ) {
			foreach ( $more as $k => $v ) {
				$config[$k]	=	$v;
			}
		}
		
		return $config;
	}
	
	// initScript
	public static function initScript( $type, &$elem, $options = array() )
	{
		$doc	=	JFactory::getDocument();
		$css	=	'';
		$js		=	'';
		$js2	=	'';
		$js3	=	'';
		if ( $type == 'field' ) {
			if ( isset( $options['doTranslation'] ) ) {
				if ( is_array( $options['doTranslation'] ) ) {
					$flag		=	'&nbsp;';
					$function	=	'after';
					$selector	=	$options['doTranslation']['id'];
					if ( is_null( $elem->bool8 ) ) {
						$elem->bool8	=	$options['doTranslation']['value'];
					}
				} else {
					$flag		=	'';
					$function	=	'before';
					$selector	=	'sortable_core_options';
					if ( is_null( $elem->bool8 ) ) {
						$elem->bool8	=	$options['doTranslation'];
					}
				}
				if ( $elem->bool8 == 1 ) {
					$c0 	=	'';
					$c1 	=	'checked="checked"';
					$class	=	'publish icon-flag';
				} else {
					$c0		=	'checked="checked"';
					$c1 	=	'';
					$class	=	'unpublish icon-flag';
				}
				$btn	=	( JCck::on() ) ? 'btn btn-micro ' : '';
				$flag	.=	'<a href="javascript: void(0);" id="bool8" class="'.$btn.'jgrid"><span class="hasTooltip state '.$class.'" title="'.JText::_( 'COM_CCK_TRANSLATE_OPTIONS' ).'"></span></a>'
						.	'<input type="radio" id="bool80" name="bool8" value="0" '.$c0.' style="display:none;" />'
						.	'<input type="radio" id="bool81" name="bool8" value="1" '.$c1. ' style="display:none;" />';
				$js2	.=	'$("#'.$selector.'").'.$function.'("'.addslashes( $flag ).'");';
				if ( JCck::on() ) {
					$js2.=	'$("a#bool8 .hasTooltip").tooltip({});';
				}
				$js2	.=	'$("#bool8").click(function(){ if ( $("#bool80").prop("checked") == true ) {'
						.	'$("#bool8 span").removeClass("unpublish").addClass("publish"); $("#bool81").prop("checked", true); $("#bool80").prop("checked", false); } else {'
						.	'$("#bool8 span").removeClass("publish").addClass("unpublish"); $("#bool81").prop("checked", false); $("#bool80").prop("checked", true); } });';
			}
			if ( isset( $options['hasOptions'] ) && $options['hasOptions'] === true ) {
				$html		=	'';
				if ( isset( $options['customAttr'] ) ) {
					$label		=	isset( $options['customAttrLabel'] ) ? $options['customAttrLabel'] : JText::_( 'COM_CCK_CUSTOM_ATTRIBUTES' );
					$html		.=	'<input type="checkbox" id="toggle_attr" name="toggle_attr" value="1" />'
								.	'<label for="toggle_attr" class="toggle_attr">'.$label.'</label>';
					$attribs	=	'';
					
					if ( is_array( $options['customAttr'] ) ) {
						$keys	=	array();
						$js3	=	'var disp = ($("#toggle_attr").prop("checked") !== false) ? \'style="display: block"\' : "";';
						$n		=	0;
						foreach ( $options['customAttr'] as $i=>$customAttr ) {
							$attribs	.=	'<div class="attr">'
										.	'<input type="text" id="attr__\'+k+\'" name="json[options2][options][\'+k+\']['.$customAttr.']" value="\'+val['.$i.']+\'"'
										.	' class="inputbox mini" size="10" />'
										.	'</div>';
							$keys[]		=	$customAttr;
							$js3		.=	'$("#sortable_core_options>div:last input:text[name=\'string[options][]\']").parent().append(\'<div class="attr"\'+disp+\'><input type="text" id="attr__0" name="json[options2][options][\'+(++cur)+\']['.$customAttr.']" value="" class="inputbox mini" size="10" /></div>\');';
						}
						$keys		=	implode( ',', $keys );
					} elseif ( $options['customAttr'] ) {
						$js3		=	'var disp = ($("#toggle_attr").prop("checked") !== false) ? \'style="display: block"\' : "";';
						$n			=	(int)$options['customAttr'];
						$attribs	=	'<div class="clr"></div><div class="attr">';
						for ( $i = 0; $i < $n; $i++ ) {
							$css		=	( ( $i + 2 ) % 3 == 0 ) ? ' middle' : '';
							$attribs	.=	'<input type="text" id="attr__\'+k+\'_'.($i + 1).'" name="json[options2][options][\'+k+\'][attr][]" value="\'+val['.$i.']+\'" class="inputbox input-mini mini2'.$css.'" size="8" />';
						}
						$attribs	.=	'</div>';
						$location	=	( $elem->location ) ? explode( '||', $elem->location ) : array( 0=>'', 1=>'', 2=>'' );
						$html		.=	'<div class="clr"></div><div class="attr">';
						for ( $i = 0; $i < $n; $i++ ) {
							$css	=	( ( $i + 2 ) % 3 == 0 ) ? ' middle' : '';
							$html	.=	'<input type="text" id="location'.($i + 1).'" name="string[location][]" class="inputbox input-mini mini2'.$css.'" size="8" value="'.htmlspecialchars( @$location[$i] ).'" />';
						}
						$html		.=	'</div>';
						$js3		.=	'var content = \'<div class="clr"></div><div class="attr"\'+disp+\'>';
						for ( $i = 0; $i < $n; $i++ ) {
							if ( $i == 0 ) {
								$js3	.=	'<input type="text" id="attr__0_1" name="json[options2][options][\'+(++cur)+\'][attr][]" value="" class="inputbox input-mini mini2" size="8" />';
							} else {
								$css	=	( ( $i + 2 ) % 3 == 0 ) ? ' middle' : '';
								$js3	.=	'<input type="text" id="attr__0_1" name="json[options2][options][\'+(cur)+\'][attr][]" value="" class="inputbox input-mini mini2'.$css.'" size="8" />';
							}
						}
						$js3		.=	'</div>\';';
						$keys		=	'';
					}
					if ( !isset( $options['options'] ) ) {
						$options['options']	=	JCckDev::fromJSON( $elem->options2 );
					}
					if ( isset( $options['options']['options'] ) ) {
						$opts	=	json_encode( $options['options']['options'] );
					} else {
						$opts	=	'{}';
					}
					$js		=	'
								var keys = "'.$keys.'";
								var len = 0; var len2 = "'.$n.'";
								if (keys!="") {keys = keys.split(","); len = keys.length;}
								var val = []; for(i=0;i<len2;i++){val[i] = "";}
								var values = $.parseJSON("'.addslashes( $opts ).'");
								if (values.length>0) {
									$("div#sortable_core_options input[name=\'string[options][]\']").each(function(k, v) {
										if (len) {
											if (values[k]) {for(i=0; i<len; i++) {if (values[k][keys[i]] !== undefined) {val[i] = values[k][keys[i]];}}}
										} else {
											if (values[k]) {
												for(i=0;i<len2;i++){if (values[k].attr[i] !== undefined) {val[i] = values[k].attr[i];}}
											}
										}
										$(this).parent().append(\''.$attribs.'\');
									});											
								} else {
									$("div#sortable_core_options input[name=\'string[options][]\']").each(function(k, v) {
										$(this).parent().append(\''.$attribs.'\');
									});	
								}
								';
					$js2	.=	'$("div#layer").on("change", "input#toggle_attr", function() { $("div.attr, #location").toggle(); });';
				}
				if ( isset( $options['fieldPicker'] ) ) {
					$fields	=	JCckDatabase::loadObjectList( 'SELECT a.title as text, a.name as value FROM #__cck_core_fields AS a'
															. ' WHERE a.published = 1 AND a.storage !="dev" AND a.name != "'.$elem->name.'" ORDER BY text' );
					$fields	=	is_array( $fields ) ? array_merge( array( JHtml::_( 'select.option', '', '- '.JText::_( 'COM_CCK_ADD_A_FIELD' ).' -' ) ), $fields ) : array();
					$elem->init['fieldPicker']	=	JHtml::_( 'select.genericlist', $fields, 'fields_list', 'size="1" class="inputbox select" style="max-width:175px;"',
															  'value', 'text', '', 'fields_list' );
					$isNew	=	( !$elem->options ) ? 1 : 0;
					$js2	.=	'var cur = 9999; var isNew = '.$isNew.';
								$("ul.adminformlist").on("change", "select#fields_list", function() {
									var val = $(this).val();
									if (val) {
										$("#sortable_core_options>div:last .button-add-core_options").click();
										$("#sortable_core_options>div:last input:text[name=\'string[options][]\']").val(val);
										'.$js3.'
									}
									if (isNew) {
										var attr = "input:text[name=\'json\[options2\]\[options\]\[0\]\[direction\]\']";
										if ($(attr).length) { $(attr).remove(); }
									} isNew = 0;
								});
								';
					if ( !$elem->options ) {
						//$js2	.=	'$("#sortable_core_options>div:last .button-add-core_options").click();';
					}
					$css	.=	'.button-add{display:none;}';
					if ( !$elem->options ) {
						$css	.=	'#collection-group-wrap-core_options__0{display:none;}';
					}
					$js3	=	'';
				} else {
					$js3	=	'(function($){ var cur = 9999; $.fn.JCckFieldxAddAfter = function() {'.$js3.' $(this).next().find(".collection-group-form").append(content);} })(jQuery);';
				}
				if ( $html ) {
					$html	=	'<div class="clr"></div><div>'.$html.'</div>';
					$js		=	'if ($("#sortable_core_options")) { '.$js.' $("#sortable_core_options").parent().append("'.addslashes( $html ).'"); }';
				}
			}
			if ( $css ) {
				echo '<style type="text/css">'.$css.'</style>';
			}
			if ( $js || $js2 ) {
				echo '<script type="text/javascript">'.'jQuery(document).ready(function($){'.$js.$js2.'});'.$js3.'</script>';
			}
			
			return;
		}
		
		if ( $elem->name ) {
			JFactory::getLanguage()->load( 'plg_cck_field_'.$type.'_'.$elem->name, JPATH_ADMINISTRATOR, null, false, true );
		}
		Helper_Include::addTooltip( 'span[title].qtip_cck', 'left center', 'right center' );
		
		if ( $type == 'validation' ) {
			return;
		}
		$js	=	'
				(function ($){
					JCck.Dev = {
						reset: function() {
							var elem = "'.$elem->id.'_'.$type.'_options";
							parent.jQuery("#"+elem).val("");
							this.close();
						},
						submit: function() {
							if ( $("#adminForm").validationEngine("validate") === true ) {
								var elem = "'.$elem->id.'_'.$type.'_options";
								var data = {};
								$.each(cck_dev, function(k, v) {
									if(!$("#"+v).length) {
										var temp = [];
										$("[name=\""+v+"\[\]\"]").each(function(i) {
											temp[i] = $(this).val();
										});
										data[v] = temp.join("||");
									} else {
										data[v] = $("#"+v).myVal();
									}
								});
								var encoded = $.toJSON(data);
								parent.jQuery("#"+elem).val(encoded);
								this.close();
								return;
							}
						}
					}
					$(document).ready(function(){
						var elem = "'.$elem->id.'_'.$type.'_options";
						var encoded = parent.jQuery("#"+elem).val();
						var data = ( encoded != "" ) ? $.evalJSON(encoded) : "";
						$.each(data, function(k, v) {
							if(!$("#"+k).length) {
								var temp = v.split("||");
								var len = temp.length;
								for(i = 0; i < len; i++) {
									if ( i+1 < len ) { $("#sortable_core_dev_texts>div:last .button-add-core_dev_texts").click(); }
									$("[name=\""+k+"\[\]\"]:eq("+i+")").myVal(temp[i]);
								}
							} else {
								$("#"+k).myVal( v );
							}
						});
					});
				})(jQuery); 
			';
		
		$doc->addScriptDeclaration( $js );
	}
	
	// preload
	public static function preload( $fieldnames )
	{
		$preload	=	array();
		$fields_in	=	implode( '","', $fieldnames );
		$fields		=	JCckDatabase::loadObjectList( 'SELECT a.* FROM #__cck_core_fields AS a WHERE a.name IN ("'.$fields_in.'")', 'name' ); //#
		
		foreach ( $fieldnames as $f ) {
			$preload[$f]	=	( isset( $fields[$f] ) ) ? $fields[$f] : $f;
		}
		
		return $preload;
	}
	
	// validate
	public static function validate( $config, $id = 'adminForm' )
	{
		$config['validation']			=	count( $config['validation'] ) ? implode( ',', $config['validation'] ) : '"null":{}';
		$config['validation_options']	=	new JRegistry( array( 'validation_background_color'=>'#242424', 'validation_color'=>'#ffffff', 'validation_position'=>'topRight', 'validation_scroll'=>0 ) );
		
		Helper_Include::addValidation( $config['validation'], $config['validation_options'], $id );
		
		if ( isset( $config['fields'] ) && count( $config['fields'] ) ) {
			JFactory::getDocument()->addScriptDeclaration( 'var cck_dev = '.json_encode( $config['fields'] ).';' );
		}
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Fields & Markup
	
	// get
	public static function get( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array() )
	{
		return JCckDevField::get( $field, $value, $config, $inherit, $override );
	}
	
	// getEmpty
	public static function getEmpty( $properties )
	{
		require_once JPATH_ADMINISTRATOR.'/components/com_cck/tables/field.php';
		$field	=	JTable::getInstance( 'field', 'CCK_Table' );
		
		if ( is_array( $properties ) ) {
			foreach ( $properties as $k => $v ) {
				$field->$k	=	$v;
			}
		}
		
		return $field;
	}
	
	// getForm
	public static function getForm( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array() )
	{
		$field	=	JCckDevField::get( $field, $value, $config, $inherit, $override );
		if ( ! $field ) {
			return '';
		}
		
		$config['fields'][]	=	$field->storage_field;
		$html				=	( isset( $field->form ) ) ? $field->form : '';
		if ( isset( $inherit['after'] ) ) {
			$html			.=	$inherit['after'];
		}
		
		return $html;
	}
	
	// renderForm
	public static function renderForm( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array(), $class = '' )
	{	
		$field	=	JCckDevField::get( $field, $value, $config, $inherit, $override );
		if ( ! $field ) {
			return '';
		}
		
		$config['fields'][]	=	$field->storage_field;
		$tag				=	( $field->required ) ? '<span class="star"> *</span>' : '';
		$class				=	( $class ) ? ' class="'.$class.'"' : '';
		$html				=	( isset( $field->form ) ) ? $field->form : '';
		if ( isset( $inherit['after'] ) ) {
			$html			.=	$inherit['after'];
		}
		$html				=	'<li'.$class.'><label>'.$field->label.$tag.'</label>'.$html.'</li>';
		
		return $html;
	}
	
	// renderBlank
	public static function renderBlank( $html = '', $label = '' )
	{
		return '<li><label>'.$label.'</label>'.$html.'</li>';
	}
	
	// renderHelp
	public static function renderHelp( $type, $url = '' )
	{
		if ( !$url ) {
			return;
		}
		
		$app	=	JFactory::getApplication();
		$raw	=	false;
		switch ( $type ) {
			case 'addon':
				$raw	=	true;
				break;
			case 'link':
			case 'live':
			case 'typo':
				$type	=	'plugin';
				break;
			case 'validation':
				$type	=	'plugin';
				$raw	=	true;
				break;
			default:
				break;
		}
		
		$app->cck_markup_closed	=	true;
		
		$link	=	'http://www.seblod.com/support/documentation/'.$url.'?tmpl=component';
		$opts	=	'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=685,height=600';
		$help	=	'<div class="clr"></div><div class="how-to-setup">'
				.	'<a href="'.$link.'" onclick="window.open(this.href, \'targetWindow\', \''.$opts.'\'); return false;">' . JText::_( 'COM_CCK_HOW_TO_SETUP_THIS_'.$type ) . '</a>'
				.	'</div>';
		
		return ( $raw !== false ) ? $help : '</ul>'.$help.'</div>';
	}
	
	// renderLegend
	public static function renderLegend( $legend, $tooltip = '', $tag = '1' )
	{
		if ( $tooltip != '' ) {
			$tag		=	'<span class="star"> &sup'.$tag.';</span>';
			$tooltip	=	' class="hasTooltip qtip_cck" title="'.$tooltip.'"';
		} else {
			$tag		=	'';
			$tooltip	=	'';
		}
		
		return '<div class="legend top left"><span'.$tooltip.'>'.$legend.$tag.'</span></div>';
	}
	
	// renderSpacer
	public static function renderSpacer( $legend, $tooltip = '', $tag = '2', $options = array( 'class_sfx'=>'-2cols' ) )
	{
		$app	=	JFactory::getApplication();
		
		if ( isset( $app->cck_markup_closed ) && $app->cck_markup_closed === true ) {
			$close					=	'';
			$app->cck_markup_closed	=	false;
		} else {
			$close	=	'</ul></div>';
		}
		if ( $tooltip ) {
			$legend	=	'<span class="hasTooltip qtip_cck" title="'.$tooltip.'">'.$legend.'<span class="star"> &sup'.$tag.';</span></span>';
		}
		
		return $close.'<div class="seblod"><div class="legend top left">'.$legend.'</div><ul class="adminformlist adminformlist'.$options['class_sfx'].'">';
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Variables Manipulation
	
	// fromJSON
	public static function fromJSON( $data = '', $format = 'array' )
	{
		if ( ! $data || ! is_string( $data )  ) {
			return ( $format == 'array' ) ? array() : new stdClass;
		}
		
		$method		=	'to'.ucfirst( $format );
		$registry	=	new JRegistry;
		$registry->loadString( $data, 'JSON' );
		
		return $registry->$method();
	}
	
	// toJSON
	public static function toJSON( $data = '' )
	{
		$registry	=	new JRegistry;
		$registry->loadArray( $data );

		return $registry->toString();
	}
	
	// fromSTRING
	public static function fromSTRING( $data = '', $glue = '||', $format = 'array' )
	{
		// todo: object
		if ( ! $data || ! is_string( $data )  ) {
			return ( $format == 'array' ) ? array() : new stdClass;
		}
		
		return ( $glue != '' ) ? explode( $glue, $data ) : array( $data );
	}
	
	// toSTRING
	public static function toSTRING( $data = '', $glue = '||' )
	{
		// todo: object
		if ( ! is_array( $data ) ) {
			return '';
		}
		
		return implode( $glue, $data );
	}
	
	// toSafeSTRING
	public static function toSafeSTRING( $string )
	{
		$str	=	str_replace( '_', ' ', $string );
		$str	=	JFactory::getLanguage()->transliterate( $str );
		$str	=	preg_replace( array( '/\s+/', '/[^A-Za-z0-9_]/' ), array( '_', '' ), $str );
		$str	=	trim( strtolower( $str ) );
		
		return $str;
	}
	
	// fromXML
	public static function fromXML( $data = '', $isFile = true )
	{
		libxml_use_internal_errors( true );
		
		if ( $isFile ) {
			$xml	=	simplexml_load_file( $data, 'JCckDevXml' );
		} else {
			$xml	=	simplexml_load_string( $data, 'JCckDevXml' );
		}
		
		if ( empty( $xml ) ) {
			JError::raiseWarning( 100, JText::_( 'JLIB_UTIL_ERROR_XML_LOAD' ) );
			
			if ( $isFile ) {
				JError::raiseWarning( 100, $data );
			}
			foreach ( libxml_get_errors() as $error ) {
				JError::raiseWarning( 100, 'XML: ' . $error->message );
			}
		}
		
		return $xml;
	}
}
?>