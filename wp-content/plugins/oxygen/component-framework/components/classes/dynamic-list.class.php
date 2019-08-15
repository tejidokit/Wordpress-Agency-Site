<?php

/**
 * Dynamic list 
 * 
 * @since 2.1
 */

class Oxygen_VSB_Dynamic_List extends CT_Component {

    public $param_array = array();
    public $css_util;
    public $query;
    public $action_name = "oxy_get_dynamic_data_query";
    public $template_file = "dynamic-list.php"; 

    function __construct($options) {

        // run initialization
        $this->init( $options );

        // Add shortcodes
        add_shortcode( $this->options['tag'], array( $this, 'add_shortcode' ) );

        // change component button place
        remove_action("ct_toolbar_fundamentals_list", array( $this, "component_button" ) );
        add_action("oxygen_helpers_components_logic", array( $this, "component_button" ) );

        // output styles
        // add_filter("ct_footer_styles", array( $this, "template_css" ) );
        // add_filter("ct_footer_styles", array( $this, "params_css" ) );

        //add specific options to Basic Styles tab
        add_action("ct_toolbar_component_settings", array( $this, "settings"), 0 );
        
        // output list of templates
        // add_action("ct_builder_ng_init", array( $this, "templates_list") );

        // render preveiew with AJAX
         add_filter("template_include", array( $this, "single_template"), 100 );
    }

    
    /**
     * Add a [oxy_dynamic_list] shortcode to WordPress
     *
     * @since 2.1
     * @author Gagan S Goraya
     */

    function add_shortcode( $atts, $content, $name ) {

        $options = $this->set_options( $atts );

        $this->setQuery($options);

        ob_start();
        
        global $oxygen_vsb_css_caching_active;
        
        ?><div id="<?php echo esc_attr($options['selector']); ?>" class="<?php if(isset($options['classes'])) echo esc_attr($options['classes']); ?>"><?php



            if(class_exists('ACF') && isset($options['use_acf_repeater']) && $options['use_acf_repeater'] !== 'false') {
                
                if(isset($options['acf_repeater']) && !empty($options['acf_repeater'])) {
                    $size_of_data = count(get_field($options['acf_repeater']));
                    global $oxygen_vsb_acf_rep_array;
                    for($i = 0; $i < $size_of_data; $i++) {
                        $oxygen_vsb_acf_rep_array = $i;
                        echo do_shortcode( $content );
                        // if it is the css being rendered, the only one iteration is sufficient
                        if(isset($oxygen_vsb_css_caching_active) && $oxygen_vsb_css_caching_active === true) {
                            break;
                        }
                    }
                    $oxygen_vsb_acf_rep_array = null;
                }
            }
            else {
                // global variable to hold this query so that the oxy dynamic shortcodes use it
                global $oxy_vsb_use_query;
                $oxy_vsb_use_query = $this->query;

                while ($this->query->have_posts()) {
                    $this->query->the_post();
                    echo do_shortcode( $content );
                    // if it is the css being rendered, the only one iteration is sufficient
                    if(isset($oxygen_vsb_css_caching_active) && $oxygen_vsb_css_caching_active === true) {
                        break;
                    }
                }

                $oxy_vsb_user_query = false; // let the rest of the system rely on the global wp_query
                wp_reset_query();
            }
        ?></div><?php

        return ob_get_clean();
    }

    function setQuery($options) {
        // manual
        if (isset($options['query_args']) && isset($options['wp_query']) && $options['wp_query']=='manual') {

            $args = $options['query_args'];
            /* https://wordpress.stackexchange.com/questions/120407/how-to-fix-pagination-for-custom-loops 
            apparently doesn't work on static front pages? */
            $args .= get_query_var( 'paged' ) ? '&paged='.get_query_var( 'paged' ) : '';

            $this->query = new WP_Query($args);
        }

        // query builder
        elseif (isset($options['query_args']) && isset($options['wp_query']) && $options['wp_query']=='custom') {
            
            $args = array();
            
            // post type
            if ($options['query_post_ids']) {
                $args['post__in'] = explode(",",$options['query_post_ids']);
            }
            else {
                $args['post_type'] = $options['query_post_types'];
            }

            // filtering
            if (is_array($options['query_taxonomies_any'])) {
                
                $taxonomies = array();
                $args['tax_query'] = array(
                    'relation' => 'OR',
                );

                // sort IDs by taxonomy slug
                foreach ($options['query_taxonomies_any'] as $value) {
                    $value = explode(",",$value);
                    $key = $value[0];
                    if ($key == "tag") {
                        $key = "post_tag";
                    }
                    $taxonomies[$key][] = $value[1];
                }

                foreach ($taxonomies as $key => $value) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $key,
                        'terms'    => $value,
                    );
                }
            }
            if (is_array($options['query_taxonomies_all'])&&!empty($options['query_taxonomies_all'])) {
                
                $taxonomies = array();
                $args['tax_query'] = array(
                    'relation' => 'AND',
                );

                // sort IDs by taxonomy slug
                foreach ($options['query_taxonomies_all'] as $value) {
                    $value = explode(",",$value);
                    $key = $value[0];
                    if ($key == "tag") {
                        $key = "post_tag";
                    }
                    $taxonomies[$key][] = $value[1];
                }

                foreach ($taxonomies as $key => $value) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $key,
                        'terms'    => $value,
                        'operator' => 'AND'
                    );
                }
            }
            if ($options['query_authors']) {
                $args['author__in'] = $options['query_authors'];
            }

            // order
            $args['order']   = $options['query_order'];
            $args['orderby'] = $options['query_order_by'];

            if ($options['query_all_posts']==='true') {
                $args['nopaging'] = true;
            }

            if ($options['query_ignore_sticky_posts']==='true') {
                $args['ignore_sticky_posts'] = true;
            }

            if ($options['query_count']) {
                $args['posts_per_page'] = $options['query_count'];
            }
            
            // pagination
            if (get_query_var('paged')&&!$options['query_count']) {
                $args['paged'] = get_query_var( 'paged' );
            }

            $this->query = new WP_Query($args);
        } else {
            // use the current default query
            global $wp_query;

            $this->query =  $wp_query;  
        }
    }


    function parse_shortcodes_map($shortcodes, $conditions, $logics, $options) {

        $results = array();
        $conditionResults = array();
        $logicResults = array();

        global $oxygen_vsb_global_conditions;

        if(class_exists('ACF') && isset($options['use_acf_repeater']) && $options['use_acf_repeater'] !== "false") {
            
            if(isset($options['acf_repeater']) && !empty($options['acf_repeater'])) {

                $shortcode_results = array();
                $size_of_data = count(get_field($options['acf_repeater']));

                foreach($shortcodes as $key => $item) {
                    
                    $shortcode_results[$key] = do_shortcode($item);
                    
                    $array_form = json_decode($shortcode_results[$key]);

                    if(is_array($array_form)) {
                        if(sizeof($array_form) > $size_of_data) {
                            $size_of_data = sizeof($array_form);
                        }
                        $shortcode_results[$key] = $array_form;
                    }

                }

                if($size_of_data) {
                    global $oxygen_vsb_acf_rep_array;
                    for($i = 0; $i < $size_of_data; $i++) {
                        $oxygen_vsb_acf_rep_array = $i;
                        $result = array();

                        // $result[$repeater_key] = $item;

                        foreach($shortcode_results as $otherKey => $otherItem) {
                            
                            if(is_array($otherItem)) {
                                if(isset($otherItem[$i])) {
                                    $result[$otherKey] = $otherItem[$i];
                                } else {
                                    $result[$otherKey] = "";
                                }
                            }
                            else {
                                $result[$otherKey] = $otherItem;
                            }
                        }

                        $results[] = $result;


                        foreach($conditions as $key => $condition) {

                            if( is_array($condition) &&
                                isset($oxygen_vsb_global_conditions[$condition['name']]) && 
                                isset($oxygen_vsb_global_conditions[$condition['name']]['callback']) &&
                                function_exists($oxygen_vsb_global_conditions[$condition['name']]['callback'])
                            ) {
                                
                                if($condition['name']=='ZZOXYVSBDYNAMIC') {
                                   
                                    $conditions[$key]['result'] = call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator'], $condition['oxycode']);                 
                                }
                                else {
                                    $conditions[$key]['result'] = call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator']);
                                }
                            } 

                        }

                       $conditionResults[] = $conditions;

                        $logicResult = array();
                        foreach($logics as $logicKey => $logicItem) {
                            $logicResult[$logicKey] = ct_eval_oxy_condition($logicItem)?1:0;
                        }

                        $logicResults[] = $logicResult;
                    }

                    $oxygen_vsb_acf_rep_array = null;

                }
            }
            
            
            return array(
                'results' => $results, 
                'conditions' => $conditionResults,
                'logicResults' => $logicResults
            );


        }



        $this->setQuery($options);

        
        // global variable to hold this query so that the oxy dynamic shortcodes use it
        global $oxy_vsb_use_query;
        $oxy_vsb_use_query = $this->query;

        while ($this->query->have_posts()) {
            $this->query->the_post();

            $result = array();
            $logicResult = array();
            foreach($shortcodes as $key => $item) {
                $result[$key] = do_shortcode($item);
            }

            foreach($conditions as $key => $condition) {

                if( is_array($condition) &&
                    isset($oxygen_vsb_global_conditions[$condition['name']]) && 
                    isset($oxygen_vsb_global_conditions[$condition['name']]['callback']) &&
                    function_exists($oxygen_vsb_global_conditions[$condition['name']]['callback'])
                ) {
                
                    if($condition['name']=='ZZOXYVSBDYNAMIC') {
                        $conditions[$key]['result'] = call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator'], $condition['oxycode']);                 
                    }
                    else {
                        $conditions[$key]['result'] = call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator']);
                    }
                } 

            }

            foreach($logics as $key => $item) {
                $logicResult[$key] = ct_eval_oxy_condition($item)?1:0;
            }

            $logicResults[] = $logicResult;


            $results[] = $result;
            $conditionResults[] = $conditions;
        }

        return array('results' => $results, 'conditions' => $conditionResults, 'logicResults' => $logicResults);
    }

    /**
     * Basic Styles settings
     *
     * @since 2.0
     * @author Ilya K.
     */

    function settings () { 

        global $oxygen_toolbar; ?>

        <div class="oxygen-sidebar-flex-panel oxygen-sidebar-dynamic-list-panel"
            ng-hide="!isActiveName('oxy_dynamic_list')">

            <div class="oxygen-sidebar-advanced-subtab" 
                ng-click="switchTab('dynamicList', 'query')" 
                ng-show="!hasOpenTabs('dynamicList')">
                    <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/general-config.svg">
                    <?php _e("Query", "oxygen"); ?>
                    <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
            </div>

            <div class="oxygen-sidebar-advanced-subtab" 
                ng-click="switchTab('dynamicList', 'layout')" 
                ng-show="!hasOpenTabs('dynamicList')">
                    <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/general-config.svg">
                    <?php _e("Layout", "oxygen"); ?>
                    <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
            </div>

            <div class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList', 'layout')">
                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="tabs.dynamicList=[]">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="tabs.dynamicList=[]"><?php _e("Dynamic List","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Layout","oxygen"); ?></div>
                </div>
                <?php
                    CT_Component::component_params(array(
                        array(
                            "type"          => "flex-layout",
                            "heading"       => __("Layout Child Elements", "oxygen"),
                            "param_name"    => "flex-direction",
                            "css"           => true,
                        ),
                        array(
                            "type"          => "checkbox",
                            "heading"       => __("Allow multiline"),
                            "param_name"    => "flex-wrap",
                            "value"         => "",
                            "true_value"    => "wrap",
                            "false_value"   => "",
                            "condition"     => "flex-direction=row"
                        ),
                        array(
                            "type" => "positioning",
                        ), 
                    ));
                ?>
            </div>

            <div  class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList','query')">

                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="tabs.dynamicList=[]">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="tabs.dynamicList=[]"><?php _e("Dynamic List","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Query","oxygen"); ?></div>
                </div>

                <div class='oxygen-control-row'
                    ng-show="iframeScope.component.options[iframeScope.component.active.id]['model']['use_acf_repeater']!='true'">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("WP Query","oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class='oxygen-button-list'>
                                <?php $oxygen_toolbar->button_list_button('wp_query', 'default'); ?>
                                <?php $oxygen_toolbar->button_list_button('wp_query', 'custom'); ?>
                                <?php $oxygen_toolbar->button_list_button('wp_query', 'manual'); ?>
                            </div>
                        </div>
                    </div>
                </div>

               

                <div class='oxygen-control-row'
                    ng-show="iframeScope.component.options[iframeScope.component.active.id]['model']['wp_query']=='manual' && iframeScope.component.options[iframeScope.component.active.id]['model']['use_acf_repeater']!='true'">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("Query Params","oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class="oxygen-textarea">
                                <textarea class="oxygen-textarea-textarea"
                                    <?php $this->ng_attributes('query_args'); ?>></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div ng-show="iframeScope.component.options[iframeScope.component.active.id]['model']['wp_query']=='custom' && iframeScope.component.options[iframeScope.component.active.id]['model']['use_acf_repeater']!='true'">
                    
                    <div class="oxygen-sidebar-advanced-subtab" 
                        ng-click="switchTab('dynamicList', 'postType')">
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/styles.svg">
                            <?php _e("Post Type", "oxygen"); ?>
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
                    </div>

                    <div class="oxygen-sidebar-advanced-subtab" 
                        ng-click="switchTab('dynamicList', 'filtering')">
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/styles.svg">
                            <?php _e("Filtering", "oxygen"); ?>
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
                    </div>

                    <div class="oxygen-sidebar-advanced-subtab" 
                        ng-click="switchTab('dynamicList', 'order')">
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/styles.svg">
                            <?php _e("Order", "oxygen"); ?>
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
                    </div>

                    <div class="oxygen-sidebar-advanced-subtab" 
                        ng-click="switchTab('dynamicList', 'count')">
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/panelsection-icons/styles.svg">
                            <?php _e("Count", "oxygen"); ?>
                            <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg">
                    </div>

                </div>

                
            <?php
                if(class_exists('ACF')) { // acf repeater fields tab
            ?>

                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class="oxygen-checkbox">
                            <input type="checkbox"
                                ng-true-value="'true'" 
                                ng-false-value="'false'"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['use_acf_repeater']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','use_acf_repeater')">
                            <div class='oxygen-checkbox-checkbox'
                                ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.getOption('use_acf_repeater')=='true'}">
                                <?php _e("Use ACF Repeater","oxygen"); ?>
                            </div>
                        </label>
                    </div>
                </div>
            
                <div class='oxygen-control-row'
                    ng-show="iframeScope.component.options[iframeScope.component.active.id]['model']['use_acf_repeater']=='true'">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("ACF Repeater Field","oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class='oxygen-button-list'>
                                <label 
                                ng-repeat="(repeater, repeaterObj) in iframeScope.acfRepeaters"
                                class="oxygen-button-list-button" ng-class="{'oxygen-button-list-button-active':iframeScope.getOption('acf_repeater')==repeater,'oxygen-button-list-button-default':iframeScope.isInherited(iframeScope.component.active.id,'acf_repeater',repeater)==true}">
                                    <input type="radio" name="acf_repeater" value="{{repeater}}" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['acf_repeater']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'acf_repeater');iframeScope.checkResizeBoxOptions('acf_repeater')" ng-click="radioButtonClick(iframeScope.component.active.name, 'acf_repeater', repeater)">
                                        {{repeaterObj.label}}     </label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>

                <div class="oxygen-control-row oxygen-control-row-bottom-bar">
                    <a href="#" class="oxygen-apply-button"
                        ng-click="iframeScope.dynamicListAction()">
                        <?php _e("Apply Query Params", "oxygen"); ?>
                    </a>
                </div>

            </div>

            <div class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList', 'postType')">

                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="switchTab('dynamicList', 'query')">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="switchTab('dynamicList', 'query')"><?php _e("Query","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Post Type","oxygen"); ?></div>
                </div>

                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("Post Type", "oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <select id="oxy-easy-posts-post-type" name="oxy-easy-posts-post-type[]" multiple="multiple"
                                ng-init="initSelect2('oxy-easy-posts-post-type','Choose custom post types...')"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_post_types']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_post_types')">
                                <?php $custom_post_types = get_post_types();
                                $exclude_types  = array( "ct_template", "nav_menu_item", "revision" );
                                foreach($custom_post_types as $item) {
                                    if(!in_array($item, $exclude_types)) {?>
                                        <option value="<?php echo esc_attr( $item ); ?>"><?php echo sanitize_text_field( $item ); ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("Or manually specify IDs (comma separated)", "oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class='oxygen-input'>
                                <input type="text" spellcheck="false"
                                    ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_post_ids']"
                                    ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_post_ids')">
                            </div>
                        </div>
                    </div>

                </div>
                    
                <div class="oxygen-control-row oxygen-control-row-bottom-bar">
                    <a href="#" class="oxygen-apply-button"
                        ng-click="iframeScope.dynamicListAction()">
                        <?php _e("Apply Query Params", "oxygen"); ?>
                    </a>
                </div>

            </div>

            <div class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList', 'filtering')">

                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="switchTab('dynamicList', 'query')">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="switchTab('dynamicList', 'query')"><?php _e("Query","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Filtering","oxygen"); ?></div>
                </div>

                <?php
                    $query_taxonomies = array(
                        'query_taxonomies_any' => __("In Any of the Following Taxonomies", "oxygen"),
                        'query_taxonomies_all' => __("Or In All of the Following Taxonomies", "oxygen")
                    );
                ?>

                <?php foreach ($query_taxonomies as $key => $value) : ?>
                    
                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php echo $value; ?></label>
                        <div class='oxygen-control'>
                            <select name="oxy-easy-posts-<?php echo $key; ?>[]" id="oxy-easy-posts-<?php echo $key; ?>" multiple="multiple"
                                ng-init="initSelect2('oxy-easy-posts-<?php echo $key; ?>','Choose taxonomies...')"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['<?php echo $key; ?>']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','<?php echo $key; ?>')">
                                <?php 
                                // get default post categories
                                $default_categories = get_categories(array('hide_empty' => 0));
                                ?>
                                    <optgroup label="<?php echo __('Categories', 'component-theme'); ?>">
                                        <?php 
                                        foreach ( $default_categories as $category ) : ?>
                                            <option value="<?php echo ((!isset($alloption) || !$alloption)?'category,':'').esc_attr( $category->term_id ); ?>">
                                                <?php echo sanitize_text_field( $category->name ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php
                                // get default post tags
                                $default_tags = get_tags(array('hide_empty' => 0));
                                ?>
                                    <optgroup label="<?php echo __('Tags', 'component-theme'); ?>">
                                        <?php 
                                        foreach ( $default_tags as $tag ) : ?>
                                            <option value="<?php echo ((!isset($alloption) || !$alloption)?'tag,':'').esc_attr( $tag->term_id ); ?>">
                                                <?php echo sanitize_text_field( $tag->name ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php
                                // get custom taxonomies
                                $args = array(
                                        "_builtin" => false
                                    );
                                $taxonomies = get_taxonomies( $args, 'object' );
                                foreach ( $taxonomies as $taxonomy ) : 
                                    $args = array(
                                        'hide_empty'    => 0,
                                        'taxonomy'      => $taxonomy->name,
                                    );
                                    $categories = get_categories( $args );
                                    if ( !isset($selected_items[$taxonomy->name]) || !$selected_items[$taxonomy->name] ) {
                                        $selected_items[$taxonomy->name] = array();
                                    }
                                    ?>
                                    <optgroup label="<?php echo sanitize_text_field( $taxonomy->labels->name . " (" . $taxonomy->name . ")" ); ?>">
                                        <?php foreach ( $categories as $category ) : ?>
                                            <option value="<?php echo ((!isset($alloption) || !$alloption)?$category->taxonomy.',':'').esc_attr( $category->term_id ); ?>">
                                                <?php echo sanitize_text_field( $category->name ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("By the following authors", "oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <select id="oxy-easy-posts-authors" name="oxy-easy-posts-authors[]" multiple="multiple"
                                ng-init="initSelect2('oxy-easy-posts-authors','Choose authors...')"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_authors']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_authors')">
                                <?php // get all users to loop
                                $authors = get_users( array( 'who' => 'authors' ) );
                                foreach ( $authors as $author ) : ?>
                                    <option value="<?php echo esc_attr( $author->ID ); ?>">
                                        <?php echo sanitize_text_field( $author->user_login ); ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php $custom_post_types = get_post_types(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="oxygen-control-row oxygen-control-row-bottom-bar">
                    <a href="#" class="oxygen-apply-button"
                        ng-click="iframeScope.dynamicListAction()">
                        <?php _e("Apply Query Params", "oxygen"); ?>
                    </a>
                </div>

            </div>

            <div class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList', 'order')">

                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="switchTab('dynamicList', 'query')">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="switchTab('dynamicList', 'query')"><?php _e("Query","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Order","oxygen"); ?></div>
                </div>

                <div class='oxygen-control-row'>
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("Order By","oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class="oxygen-select oxygen-select-box-wrapper">
                                <div class="oxygen-select-box">
                                    <div class="oxygen-select-box-current">{{$parent.iframeScope.getOption('query_order_by')}}</div>
                                    <div class="oxygen-select-box-dropdown"></div>
                                </div>
                                <div class="oxygen-select-box-options">
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','');"
                                        title="<?php _e("Unset order by", "oxygen"); ?>">
                                        &nbsp;
                                    </div>
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','date');"
                                        title="<?php _e("Set order by", "oxygen"); ?>">
                                        <?php _e("Date", "oxygen"); ?>
                                    </div>
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','modified');"
                                        title="<?php _e("Set order by", "oxygen"); ?>">
                                        <?php _e("Date Last Modified", "oxygen"); ?>
                                    </div>
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','title');"
                                        title="<?php _e("Set order by", "oxygen"); ?>">
                                        <?php _e("Title", "oxygen"); ?>
                                    </div>
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','comment_count');"
                                        title="<?php _e("Set order by", "oxygen"); ?>">
                                        <?php _e("Comment Count", "oxygen"); ?>
                                    </div>
                                    <div class="oxygen-select-box-option"
                                        ng-click="$parent.iframeScope.setOptionModel('query_order_by','menu_order');"
                                        title="<?php _e("Set order by", "oxygen"); ?>">
                                        <?php _e("Menu Order", "oxygen"); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='oxygen-control-row'>
                    <div class='oxygen-control-wrapper'>
                        <label class='oxygen-control-label'><?php _e("Order","oxygen"); ?></label>
                        <div class='oxygen-control'>
                            <div class='oxygen-button-list'>
                                <?php $oxygen_toolbar->button_list_button('query_order', 'ASC', 'ascending'); ?>
                                <?php $oxygen_toolbar->button_list_button('query_order', 'DESC', 'descending'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="oxygen-control-row oxygen-control-row-bottom-bar">
                    <a href="#" class="oxygen-apply-button"
                        ng-click="iframeScope.dynamicListAction()">
                        <?php _e("Apply Query Params", "oxygen"); ?>
                    </a>
                </div>

            </div>


            <div class="oxygen-sidebar-flex-panel"
                ng-if="isShowTab('dynamicList', 'count')">

                <div class="oxygen-sidebar-breadcrumb oxygen-sidebar-subtub-breadcrumb">
                    <div class="oxygen-sidebar-breadcrumb-icon" 
                        ng-click="switchTab('dynamicList', 'query')">
                        <img src="<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/back.svg">
                    </div>
                    <div class="oxygen-sidebar-breadcrumb-all-styles" 
                        ng-click="switchTab('dynamicList', 'query')"><?php _e("Query","oxygen"); ?></div>
                    <div class="oxygen-sidebar-breadcrumb-separator">/</div>
                    <div class="oxygen-sidebar-breadcrumb-current"><?php _e("Count","oxygen"); ?></div>
                </div>

                <div class="oxygen-control-row">
                    <label class='oxygen-control-label'><?php _e("How Many Posts?", "oxygen"); ?></label>
                </div>

                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class="oxygen-checkbox">
                            <input type="checkbox"
                                ng-true-value="'true'" 
                                ng-false-value="'false'"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_all_posts']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_all_posts')">
                            <div class='oxygen-checkbox-checkbox'
                                ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.getOption('query_all_posts')=='true'}">
                                <?php _e("All","oxygen"); ?>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="oxygen-control-row">
                    <div class='oxygen-control-wrapper'>
                        <label class="oxygen-checkbox">
                            <input type="checkbox"
                                ng-true-value="'true'" 
                                ng-false-value="'false'"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_ignore_sticky_posts']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_ignore_sticky_posts')">
                            <div class='oxygen-checkbox-checkbox'
                                ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.getOption('query_ignore_sticky_posts')=='true'}">
                                <?php _e("Ignore Sticky Posts","oxygen"); ?>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class='oxygen-control-wrapper'>
                    <label class='oxygen-control-label'><?php _e("or specify the number", "oxygen"); ?></label>
                    <div class='oxygen-control'>
                        <div class='oxygen-input'>
                            <input type="text" spellcheck="false"
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['query_count']"
                                ng-change="iframeScope.setOption(iframeScope.component.active.id,'oxy_dynamic_list','query_count')">
                        </div>
                    </div>
                </div>

                <div class="oxygen-control-row oxygen-control-row-bottom-bar">
                    <a href="#" class="oxygen-apply-button"
                        ng-click="iframeScope.dynamicListAction()">
                        <?php _e("Apply Query Params", "oxygen"); ?>
                    </a>
                </div>
            </div>

        </div>

    <?php }


}
   

    

// Create component instance
$oxygen_vsb_dynamic_list = new Oxygen_VSB_Dynamic_List( array(
            'name'  => 'Repeater',
            'tag'   => 'oxy_dynamic_list',
            'tabs'  => 'dynamicList',
            // 'params'    => array(
            //     array(
            //         "type"          => "flex-layout",
            //         "heading"       => __("Layout Child Elements", "oxygen"),
            //         "param_name"    => "flex-direction",
            //         "css"           => true,
            //     ),
            //     array(
            //         "type"          => "checkbox",
            //         "heading"       => __("Allow multiline"),
            //         "param_name"    => "flex-wrap",
            //         "value"         => "",
            //         "true_value"    => "wrap",
            //         "false_value"   => "",
            //         "condition"     => "flex-direction=row"
            //     ),
            //     array(
            //         "type" => "positioning",
            //     ), 
            // ),
            'advanced'  => array(
                "positioning" => array(
                        "values"    => array (
                            'width'      => '100',
                            'width-unit' => '%',
                            )
                    ),
                "other" => array(
                    "values" => array(
                        
                        "wp_query" => 'default',
                        "query_args" => 'author_name=admin&category_name=uncategorized&posts_per_page=2',
                        
                        "posts_per_page" => '',
                        
                        // query
                        "query_post_types" => '',
                        "query_post_ids" => '',
                        "query_taxonomies_all" => '',
                        "query_taxonomies_any" => '',
                        "query_order_by" => '',
                        "query_order" => '',
                        "query_authors" => '',
                        "query_count" => '',
                        "query_all_posts" => '',
                        "query_ignore_sticky_posts" => 'true',
                    )
                )
            ),
            'not_css_params' => array(
                    
                "wp_query",
                "query_args",
                "posts_per_page",
                
                // query
                "query_post_types",
                "query_post_ids",
                "query_taxonomies_all",
                "query_taxonomies_any",
                "query_order_by",
                "query_order",
                "query_authors",
                "query_count",
                "query_all_posts",
                "query_ignore_sticky_posts",
            )
        ));