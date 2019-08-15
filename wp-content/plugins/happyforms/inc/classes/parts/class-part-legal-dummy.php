<?php

class HappyForms_Part_Legal_Dummy extends HappyForms_Form_Part {

	public $type = 'legal_dummy';
	
	public function __construct() {
		$this->label = __( 'Legal', 'happyforms' );
		$this->description = __( 'For requiring fine print before accepting submission.', 'happyforms' );
	}
	
}