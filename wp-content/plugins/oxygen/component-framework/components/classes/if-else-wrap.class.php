<?php

/**
 * If Else Wrap Component Class
 * 
 * @since 2.1
 */

Class CT_If_Else_Wrap extends CT_Component {

	var $options;
	public $action_name = "ct_eval_conditions";
    public $template_file = "ifelse.php"; 

	function __construct( $options ) {

		// run initialization
		$this->init( $options );

		// Add shortcodes
		add_shortcode( $this->options['tag'], array( $this, 'add_shortcode' ) );

		for ( $i = 2; $i <= 16; $i++ ) {
			add_shortcode( $this->options['tag'] . "_" . $i, array( $this, 'add_shortcode' ) );
		}

		add_filter( 'template_include', array( $this, 'single_template'), 100 );

		// change component button place
		remove_action("ct_toolbar_fundamentals_list", array( $this, "component_button" ) );
		add_action("oxygen_helpers_components_logic", array( $this, "component_button" ) );
		// add specific options to Basic Styles tab
        add_action("ct_toolbar_component_settings", array( $this, "settings"), 9 );

        add_action("ct_dialog_window", array( $this, "dialog_window"));

	}


	function settings () { 
	?>

		<div class='oxygen-control-row' ng-hide="!isActiveName('ct_if_else_wrap')">

			<div class='oxygen-control-wrapper'>
				<label class='oxygen-control-label'>Conditions</label>
				<div class='oxygen-control'>
					<div class='oxygen-condition-builder'>
						<div class='oxygen-condition-builder-condition'
							ng-repeat="(index, condition) in iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']">
							<div class='oxygen-condition-builder-condition-name'
								ng-click="conditionsDialogOptions.userCondition = (condition.name == 'ZZOXYVSBDYNAMIC')?condition.oxycode:''; showDialogWindow(); dialogForms['showAddGlobalConditionName'] = true; conditionsDialogOptions.selectedIndex = index">
								<span ng-if="condition.name != 'ZZOXYVSBDYNAMIC'">{{condition.name}}</span>
								<span ng-if="condition.name == 'ZZOXYVSBDYNAMIC'">{{condition.oxycode}}</span>
								<div class='oxygen-condition-builder-change-triangle'></div>
							</div>
							<div class='oxygen-condition-builder-condition-operator'
								ng-click="showDialogWindow(); dialogForms['showAddGlobalConditionOperator'] = true; conditionsDialogOptions.selectedIndex = index">
								{{condition.operator}}
							</div>
							<div class='oxygen-condition-builder-condition-value'
								ng-click="showDialogWindow(); dialogForms['showAddGlobalConditionValue'] = true; conditionsDialogOptions.selectedIndex = index">
								<span>{{condition.value}}</span>
								<div class='oxygen-condition-builder-change-triangle'></div>
							</div>

							<div class='oxygen-condition-builder-condition-preview' 
							ng-click="showPreviewDropdown = !showPreviewDropdown; conditionsDialogOptions.selectedIndex = index"
							ng-class="{'oxygen-condition-builder-condition-preview-animating': iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['preview'] === true || iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['preview'] === false}">
								<img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/toolbar-icons/eye.svg' />
								<div ng-show="showPreviewDropdown" class="oxygen-toolbar-button-dropdown ct-global-conditions-preview-dropdown" >

									<div class="oxygen-toolbar-button-dropdown-option"
										ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = null; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">
											<?php _e("Preview Off","oxygen");?></div>
									<div class="oxygen-toolbar-button-dropdown-option"
										ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = true; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">
											<?php _e("Preview True","oxygen");?></div>
									<div class="oxygen-toolbar-button-dropdown-option"
										ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = false; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">
											<?php _e("Preview False","oxygen");?></div>
								</div>
							</div>

							<div class='oxygen-condition-builder-condition-delete'
								ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].splice(index, 1); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"
								>
								<img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/toolbar-icons/cancel-circle.svg' />
							</div>

						</div>


						<div class='oxygen-condition-builder-add-condition'>
							<a href='#' ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] = iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] || []; iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].push({name: '', operator: '', value: ''}); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"><?php _e("Add Condition","oxygen");?></a>
						</div>



					</div>
				</div>
			</div>

		</div>

	<?php
	}


	function dialog_window() {
		?>
		<div ng-if="dialogForms['showAddGlobalConditionName']" id='ct-add-global-condition-Name-dialog' class='ct-global-conditions-add-modal oxygen-data-dialog'>
			<h1>Pick a condition</h1>
			<div>
				<div class="oxygen-data-dialog-data-picker">
					<ul>
						<li ng-repeat="(name, condition) in iframeScope.globalConditions" >
							
							<span ng-if="name == 'ZZOXYVSBDYNAMIC'" ctdynamicdata="" noshadow="1" data="iframeScope.dynamicShortcodesContentMode" callback="assignOxyCodeToCondition">
								Dynamic Data
							</span>

	<span ng-if="name == 'OXYVSBDYNAMIC'">
								<input type="text" ng-model="conditionsDialogOptions.userCondition" />
									<button ctdynamicdata="" data="iframeScope.dynamicShortcodesContentMode" callback="assignOxyCodeToCondition">Data</button>
									<button
										ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']='OXYVSBDYNAMIC'; 
											iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['oxycode']=conditionsDialogOptions.userCondition;
											hideDialogWindow();
											
											iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); 
											evalGlobalConditions();">Apply</button>
							</span>

							<span ng-if="name != 'ZZOXYVSBDYNAMIC'" 
								ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']=name; 
											hideDialogWindow(); 
											iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">{{name}}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div ng-if="dialogForms['showAddGlobalConditionOperator']" id='ct-add-global-condition-Operator-dialog' class='ct-global-conditions-add-modal ct-global-conditions-choose-operator oxygen-data-dialog'>
			<h1>Choose an operator</h1>
			<div>
				<div class="oxygen-data-dialog-data-picker" ng-hide="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']">
					<h3>Select a condition first</h3>
				</div>
				<div class="oxygen-data-dialog-data-picker">
					<ul>
						<li ng-repeat="operator in iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']]['operators']" ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['operator']=operator; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"><span>{{operator}}</span></li>
					</ul>
				</div>
			</div>
		</div>

		<div ng-if="dialogForms['showAddGlobalConditionValue']" id='ct-add-global-condition-Value-dialog' class='ct-global-conditions-add-modal ct-global-conditions-choose-value oxygen-data-dialog'>
			<h1>{{iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']?(iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name'] != 'ZZOXYVSBDYNAMIC'?iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']:'Dynamic Data'):'Value'}}</h1>
			<div ng-init="usertext=''">
				<div class="oxygen-data-dialog-data-picker" ng-hide="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']">
					<h3>Select a condition first</h3>
				</div>
				<div class="oxygen-data-dialog-data-picker" ng-if="::!hasGlobalConditionsUserText()">
					<ul>
						<li ng-if="value != 'USERTEXT'" ng-repeat="value in iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']]['values']" >
							<span ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['value']=value; 
										hideDialogWindow(); 
										iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); 
										evalGlobalConditions();">{{value}}</span>
							
						</li>
					</ul>
				</div>
				<div class="oxygen-data-dialog-data-picker ct-global-conditions-custom-value" ng-if="::hasGlobalConditionsUserText()">
					<h2>Custom Value</h2>
					<ul>
						<li>
							<input type="text" value="{{iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['value']}}" ng-model="usertext" />
							<button
								ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['value']=usertext; 
									hideDialogWindow(); 
									iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); 
									evalGlobalConditions();">Apply</button>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div ng-if="dialogForms['showAddGlobalConditionPreview']" id='ct-add-global-condition-Preview-dialog' class='ct-global-conditions-add-modal'>
			<div ng-hide="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['name']">
				<h3>Select a condition first</h3>
			</div>
			<ul>
				<li ng-class="{'hilite': iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] === null}" ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = null; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">Preview Off</li>
				<li ng-class="{'hilite': iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] === true}" ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = true; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">Preview True</li>
				<li ng-class="{'hilite': iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] === false}" ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][conditionsDialogOptions.selectedIndex]['preview'] = false; hideDialogWindow(); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()">Preview False</li>
			</ul>
		</div>

		<?php
	}

	/**
	 * Add a [If_Else_Wrap] shortcode to WordPress
	 *
	 * @since 0.1
	 */

	function add_shortcode( $atts, $content, $name ) {

		if ( ! $this->validate_shortcode( $atts, $content, $name ) ) {
			return '';
		}

		$options = $this->set_options( $atts );

		preg_match('/\[ct_if_wrap(.*)\[\/ct_if_wrap[^\[]*\]/i', $content, $matches);

		$if_content = $matches[0];

		preg_match('/\[ct_else_wrap(.*)\[\/ct_else_wrap[^\[]*\]/i', $content, $matches);

		$else_content = $matches[0];

		$result = true;

		if(isset($options['globalconditions']) && is_array($options['globalconditions'])) {
			$result = CT_If_Else_Wrap::evalGlobalConditions($options['globalconditions']);
		}

		if($result) {
			$content = $if_content;
		}
		else {
			$content = $else_content;
		}

		// just for the sake of rendering the css for the true condition
		global $oxygen_vsb_css_caching_active;
		if(isset($oxygen_vsb_css_caching_active) && $oxygen_vsb_css_caching_active === true) {
			do_shortcode($if_content);
		}

		ob_start();

		?><<?php echo esc_attr($options['tag'])?> id="<?php echo esc_attr($options['selector']); ?>" class="<?php if(isset($options['classes'])) echo esc_attr($options['classes']); ?>"><?php echo do_shortcode( $content ); ?></<?php echo esc_attr($options['tag'])?>><?php

		return ob_get_clean();
	}

	static function evalGlobalConditions($conditions) {
		global $oxygen_vsb_global_conditions;
		$result = true;

		foreach($conditions as $condition) {
			if(	is_array($condition) &&
				isset($oxygen_vsb_global_conditions[$condition['name']]) && 
				isset($oxygen_vsb_global_conditions[$condition['name']]['callback']) &&
				function_exists($oxygen_vsb_global_conditions[$condition['name']]['callback'])
			) {
				
				if($condition['name']=='ZZOXYVSBDYNAMIC') {
					$result = $result && call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator'], $condition['oxycode']);					
				}
				else {
					$result = $result && call_user_func($oxygen_vsb_global_conditions[$condition['name']]['callback'], $condition['value'], $condition['operator']);
				}
			} else {

				$result = $result && filter_var($condition, FILTER_VALIDATE_BOOLEAN);
			}
		}
		return $result;
	}
}


// Create toolbar inctances
$button = new CT_If_Else_Wrap ( 

		array( 
			'name' 		=> 'Condition',
			'tag' 		=> 'ct_if_else_wrap',
			'params' 	=> array(
					array(
						"type" 			=> "tag",
						"heading" 		=> __("Tag", "oxygen"),
						"param_name" 	=> "tag",
						"value" 		=> array (
											"div" 		=> "div",
											"article" 	=> "article",
											"aside" 	=> "aside",
											"details" 	=> "details",
											"figure" 	=> "figure",
											"footer" 	=> "footer",
											"header" 	=> "header",
											"hgroup" 	=> "hgroup",
											"main" 		=> "main",
											"mark" 		=> "mark",
											"nav" 		=> "nav",
											"section" 	=> "section",
										),
						"css" 			=> false,
						"rebuild" 		=> true,
					),
				),
			'advanced' 	=> array(
					'typography' => array(
						'values' 	=> array (
								'font-family' 	=> "",
								'font-size' 	=> "",
								'font-weight' 	=> "",
							)
					),
					'flex' => array(
						'values' 	=> array (
								'display' 		 => 'flex',
								'flex-direction' => 'column',
								'align-items' 	 => 'flex-start',
								'justify-content'=> '',
								'text-align' 	 => '',
								'flex-wrap' 	 => 'nowrap',
							)
					),
                    'allowed_html' => 'post',
                    'allow_shortcodes' => true,
			),


			
		)
);

?>