<?php

/**
 * Re-usable Component Class
 * 
 * @since 0.2.3
 */

Class CT_Reusable extends CT_Component {

	var $options;

	function __construct( $options ) {

		// run initialization
		$this->init( $options );
		
		// Add shortcodes
		add_shortcode( $this->options['tag'], array( $this, 'add_shortcode' ) );

		// remove component button
		remove_action("ct_toolbar_fundamentals_list", array( $this, "component_button" ) );
	}


	/**
	 * Add a [ct_reusable] shortcode to WordPress
	 *
	 * @since 0.1
	 */

	function add_shortcode( $atts, $content, $name ) {

		if ( ! $this->validate_shortcode( $atts, $content, $name ) ) {
			return '';
		}

		// don't run the shortcodes and include re-usable CSS to page cached CSS
		global $oxygen_vsb_css_caching_active;
		if (isset($oxygen_vsb_css_caching_active) && $oxygen_vsb_css_caching_active===true) {			
			return;
		}

		ob_start();	

		$options 	= json_decode( $atts["ct_options"], true ); 
		$view_id 	= $options["view_id"];
		$view 		= get_post( $view_id );

		// needed to load cached CSS file 
		global $oxygen_vsb_css_files_to_load;
		$oxygen_vsb_css_files_to_load[] = $view_id;

		/* New Way */
		$shortcodes = get_post_meta( $view->ID, "ct_builder_shortcodes", true );
		
		$count = 0; // safety switch
		while(strpos($shortcodes, '[oxygen ') !== false && $count < 9) {
			$count++;
			$shortcodes = preg_replace_callback('/(\")(url|src|map_address|alt|background-image|oxycode|value)(\":\"[^\"]*)\[oxygen ([^\]]*)\]([^\"\[\s]*)/i', 'ct_obfuscate_oxy_url', $shortcodes);
		}
		
		$content 	= do_shortcode( $shortcodes );
		
		echo $content;

		return ob_get_clean();
	}
}


// Create toolbar inctances
global $oxygen_vsb_components;
$oxygen_vsb_components['reusable'] = new CT_Reusable ( 

		array( 
			'name' 		=> 'Reusable',
			'tag' 		=> 'ct_reusable',
			'advanced'  => array(
				'allow_shortcodes'  => true,
			)
		)
);

?>