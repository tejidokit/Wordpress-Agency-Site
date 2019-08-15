<?php

/**
 * Callback to show "Import/Export" on settings page
 *
 * @since 0.2.3
 */

function ct_export_import_callback() {

	// get saved options
	$classes 			= get_option("ct_components_classes", array() );
	$custom_selectors 	= get_option("ct_custom_selectors", array() );
	$style_sets 	= get_option("ct_style_sets", array());
	$style_folders 	= get_option("ct_style_folders", array());
	$style_sheets 		= get_option("ct_style_sheets", array() );
	$global_settings 	= get_option("ct_global_settings", array() );
	$global_colors 		= oxy_get_global_colors();


	if (!is_array($classes)) {
		$classes = array();
	}

	if (!is_array($custom_selectors)) {
		$custom_selectors = array();
	}

	if (!is_array($style_sets)) {
		$style_sets = array();
	}

	if (!is_array($style_folders)) {
		$style_folders = array();
	}

	if (!is_array($style_sheets)) {
		$style_sheets = array();
	}

	// import
	if ( isset( $_POST['ct_import_json'] ) ) {

		$import_json = sanitize_text_field( stripcslashes( $_POST['ct_import_json'] ) );

		// check if empty
		if ( empty( $import_json ) ) {
			$import_errors[] = __("Empty Import");
		}
		else {
			// try to decode
			$import_array = json_decode( $import_json, true );

			// update options
			if ( $import_array ) {
				

				if(isset($import_array['classes']) && is_array($import_array['classes'])) {
					foreach($import_array['classes'] as $key => $item) {
						if(!is_string($key)) {
							unset($import_array['classes'][$key]);
						}
					}

					$classes = array_merge( $classes, $import_array['classes'] );
					update_option("ct_components_classes", $classes );
				}

				// custom selectors
				if(isset($import_array['custom_selectors']) && is_array($import_array['custom_selectors'])) {
					$custom_selectors = array_merge( $custom_selectors, $import_array['custom_selectors'] );
					update_option("ct_custom_selectors", $custom_selectors );
				}

				// style sets
				if(isset($import_array['style_sets']) && is_array($import_array['style_sets'])) {
					$style_sets = array_merge( $style_sets, $import_array['style_sets'] );
					update_option("ct_style_sets", $style_sets );
				}

				// style folders
				if(isset($import_array['style_folders']) && is_array($import_array['style_folders'])) {
					$style_folders = array_merge( $style_folders, $import_array['style_folders'] );
					update_option("ct_style_folders", $style_folders );
					
				}

				// style sheets
				if(isset($import_array['style_sheets']) && is_array($import_array['style_sheets'])) {
					
					foreach($import_array['style_sheets'] as $key => $item) {
						foreach($style_sheets as $existing) {
							if($existing['name'] == $item['name']) {
								unset($import_array['style_sheets'][$key]);
								break;
							}
						}
					}
				

					$style_sheets = array_merge( $style_sheets, $import_array['style_sheets'] );
					update_option("ct_style_sheets", $style_sheets);
				}

				// global settings
				if(isset($import_array['global_settings']) && is_array($import_array['global_settings'])) {
					$global_settings = $import_array['global_settings'];
					update_option("ct_global_settings", $global_settings);
				}

				// global colors
				if (isset($_POST['oxy_replace_global_colors']) && $_POST['oxy_replace_global_colors'] == "yes") {
					if (is_array($import_array['global_colors'])) {
						$global_colors = $import_array['global_colors'];
						update_option("oxygen_vsb_global_colors", $global_colors);
					}
				}

				$import_success[] = __("Import success", "component-theme");
			}
			else {
				$import_errors[] = __("Wrong JSON Format", "component-theme");
			}
		}
	}

	// generate export JSON
	$export_json['classes'] 			= $classes;
  	$export_json['custom_selectors'] 	= $custom_selectors;
  	$export_json['style_sets'] 			= $style_sets;
  	$export_json['style_folders'] 		= $style_folders;
  	$export_json['style_sheets'] 		= $style_sheets;
  	$export_json['global_settings'] 	= $global_settings;
  	$export_json['global_colors'] 		= $global_colors;

  	// generate JSON object
	$export_json = json_encode( $export_json );	

	require('views/import-export-page.php');
}

?>
