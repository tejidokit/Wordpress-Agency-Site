<?php 

/**
 * Get Easy Posts instance and return rendered HTML
 * Editing something here also edit it in ajax.php!
 * 
 * @since 2.0
 * @author Ilya K.
 */

header('Content-Type: application/json');


$component_json = file_get_contents('php://input');
$component 		= json_decode( $component_json, true );
$options 		= $component['options']['original'];
$shortcodes =  $component['shortcodes'];
$conditions = $component['conditions'];
$logic = $component['logicConditions'];

	
$response = $oxygen_vsb_dynamic_list->parse_shortcodes_map($shortcodes, $conditions, $logic, $options);

echo json_encode($response);

die();