<?php 

/**
 * Deprecated in Oxygen 2.0
 *
 * Do not remove to keep old designs work!
 */

Class CT_Columns extends CT_Component {

	function __construct( $options ) {

		// run initialization
		$this->init( $options );

		// Add shortcodes
		add_shortcode( $this->options['tag'], array( $this, 'add_shortcode' ) );

		for ( $i = 2; $i <= 16; $i++ ) {
			add_shortcode( $this->options['tag'] . "_" . $i, array( $this, 'add_shortcode' ) );
		}

		// add specific settings
		//add_action("ct_toolbar_component_header", array( $this, "columns_settings") );

		// remove component button
		remove_action("ct_toolbar_fundamentals_list", array( $this, "component_button" ) );
	}


	/**
	 * Add a toolbar button
	 *
	 * @since 0.1
	 */
	function component_button() { ?>

		<div class="ct-add-component-button"
			data-searchid="<?php echo strtolower( preg_replace('/\s+/', '_', sanitize_text_field( $this->options['name'] ) ) ) ?>"
			ng-click="iframeScope.addComponents('<?php echo esc_attr($this->options['tag']); ?>','ct_column')">
			<div class="ct-add-component-icon">
				<span class="ct-icon <?php echo esc_attr($this->options['tag']); ?>-icon"></span>
			</div>
			<?php echo esc_html($this->options['name']); ?>
		</div>

	<?php }


	/**
	 * Add a [ct_columns] shortcode to WordPress
	 *
	 * @since 0.1
	 */

	function add_shortcode( $atts, $content, $name ) {
		if ( ! $this->validate_shortcode( $atts, $content, $name ) ) {
			return '';
		}

		$options = $this->set_options( $atts );

		ob_start();
		
		?><div id="<?php echo esc_attr($options['selector']); ?>" class="<?php echo esc_attr($options['classes']); ?>"><div class="ct-columns-inner-wrap"><?php echo do_shortcode( $content ); ?></div></div><?php

		return ob_get_clean();
	}

	
	/**
	 * Columns settings: columns number
	 */
	
	function columns_settings() { ?>
		
		<div class="ct-toolitem" ng-if="isActiveName('<?php echo esc_attr($this->options['tag']); ?>')">
			<h3><?php _e('Columns', 'component-theme'); ?></h3>
			<div class="ct-textbox ct-columns-number">
				<input type="number" min="1" max="12" class="oxygen-special-property not-available-for-media not-available-for-classes"
					ng-model="iframeScope.columns[iframeScope.component.active.id]"
					ng-change="iframeScope.updateColumns(iframeScope.component.active.id)"/>
			</div>
		</div>
	
	<?php }

// End CT_Columns class
}


// Create section inctance
global $oxygen_vsb_components;
$oxygen_vsb_components['columns'] = new CT_Columns( array( 
			'name' 		=> 'Columns',
			'tag' 		=> 'ct_columns',
			'params' 	=> array(
					array(
						"type" 			=> "colorpicker",
						"heading" 		=> __("Bg"),
						"param_name" 	=> "background-color",
						"value" 		=> "#ffffff",
					),
					array(
						"type" 			=> "measurebox",
						"heading" 		=> __("Spacing"),
						"param_name" 	=> "gutter",
						"value" 		=> "0",
						"css" 			=> false
					),
					array(
						"param_name" 	=> "gutter-unit",
						"value" 		=> "px",
						"hidden" 		=> true,
						"css" 			=> false
					),
				),
			'advanced' 	=> array(
					"positioning" => array(
						"values" 	=> array (
							'position' 	=> 'relative',
							)
					),
                    'allow_shortcodes' => true,
			)
		)
);
