<?php $this->settings_breadcrumbs(	
		__('Page Width','oxygen'),
		__('Global Styles','oxygen'),
		'default-styles'); ?>

<div class="oxygen-control-row">
	<div class='oxygen-control-wrapper'>
		<label class='oxygen-control-label'><?php _e("Page Width","oxygen"); ?></label>
		<div class='oxygen-measure-box'>
			<input type="text" spellcheck="false"
				ng-model="iframeScope.globalSettings['max-width']"
				ng-change="iframeScope.pageSettingsUpdate()"/>
			<div class='oxygen-measure-box-unit-selector'>
				<div class='oxygen-measure-box-selected-unit'>px</div>
			</div>
		</div>
	</div>
</div>