
					<?php $this->settings_home_breadcrumbs(__("Editor","oxygen")); ?>

					<div class="oxygen-control-row" >
						<div class="oxygen-control-wrapper">
							<label class="oxygen-control-label"><?php _e("Indicate Parents","oxygen"); ?></label>
							<div class="oxygen-control ">
								<label class="oxygen-checkbox">
									<input type="checkbox" 
										ng-true-value="'true'" 
										ng-false-value="'false'"
										ng-model="$parent.iframeScope.globalSettings.indicateParents"
										ng-change="$parent.iframeScope.unsavedChanges();$parent.iframeScope.adjustResizeBox();">
									<div class="oxygen-checkbox-checkbox"
										ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.globalSettings.indicateParents=='true'}">
									</div>
								</label>
							</div>
						</div>
					</div>