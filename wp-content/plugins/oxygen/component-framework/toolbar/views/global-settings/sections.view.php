					
					<?php $this->settings_breadcrumbs(	
							__('Sections','oxygen'),
							__('Global Styles','oxygen'),
							'default-styles'); ?>

					<div class="oxygen-control-row">
						<div class='oxygen-control-wrapper'>
							<label class='oxygen-control-label'><?php _e("Container Padding","oxygen"); ?></label>
							<div class='oxygen-control'>
								<div class='oxygen-four-sides-measure-box'>
									<div class='oxygen-measure-box'>
										<input type="text" spellcheck="false"
											data-option="container-padding-top"
											ng-model="iframeScope.globalSettings.sections['container-padding-top']"
											ng-model-options="{ debounce: 10 }"/>
										<div class='oxygen-measure-box-unit-selector'>
											<div class='oxygen-measure-box-selected-unit'>{{iframeScope.globalSettings.sections['container-padding-top-unit']}}</div>
											<div class="oxygen-measure-box-units">
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-top-unit']='px'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-top-unit']=='px'}">
													px
												</div>
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-top-unit']='%'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-top-unit']=='%'}">
													&#37;
												</div>
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-top-unit']='em'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-top-unit']=='em'}">
													em
												</div>
											</div>
										</div>
									</div>
									<div class='oxygen-four-sides-measure-box-left-right'>
										<div class='oxygen-measure-box'>
											<input type="text" spellcheck="false"
												data-option="container-padding-left"
												ng-model="iframeScope.globalSettings.sections['container-padding-left']"
												ng-model-options="{ debounce: 10 }"/>
											<div class='oxygen-measure-box-unit-selector'>
												<div class='oxygen-measure-box-selected-unit'>{{iframeScope.globalSettings.sections['container-padding-left-unit']}}</div>
												<div class="oxygen-measure-box-units">
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-left-unit']='px'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-left-unit']=='px'}">
														px
													</div>
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-left-unit']='%'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-left-unit']=='%'}">
														&#37;
													</div>
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-left-unit']='em'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-left-unit']=='em'}">
														em
													</div>
												</div>
											</div>
										</div><div class='oxygen-measure-box'>
											<input type="text" spellcheck="false"
												data-option="container-padding-right"
												ng-model="iframeScope.globalSettings.sections['container-padding-right']"
												ng-model-options="{ debounce: 10 }"/>
											<div class='oxygen-measure-box-unit-selector'>
												<div class='oxygen-measure-box-selected-unit'>{{iframeScope.globalSettings.sections['container-padding-right-unit']}}</div>
												<div class="oxygen-measure-box-units">
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-right-unit']='px'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-right-unit']=='px'}">
														px
													</div>
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-right-unit']='%'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-right-unit']=='%'}">
														&#37;
													</div>
													<div class="oxygen-measure-box-unit"
														ng-click="iframeScope.globalSettings.sections['container-padding-right-unit']='em'"
														ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-right-unit']=='em'}">
														em
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class='oxygen-measure-box'>
										<input type="text" spellcheck="false"
											data-option="container-padding-bottom"
											ng-model="iframeScope.globalSettings.sections['container-padding-bottom']"
											ng-model-options="{ debounce: 10 }"/>
										<div class='oxygen-measure-box-unit-selector'>
											<div class='oxygen-measure-box-selected-unit'>{{iframeScope.globalSettings.sections['container-padding-bottom-unit']}}</div>
											<div class="oxygen-measure-box-units">
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-bottom-unit']='px'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-bottom-unit']=='px'}">
													px
												</div>
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-bottom-unit']='%'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-bottom-unit']=='%'}">
													&#37;
												</div>
												<div class="oxygen-measure-box-unit"
													ng-click="iframeScope.globalSettings.sections['container-padding-bottom-unit']='em'"
													ng-class="{'oxygen-measure-box-unit-active':iframeScope.globalSettings.sections['container-padding-bottom-unit']=='em'}">
													em
												</div>
											</div>
										</div>
									</div>
									<div class="oxygen-apply-all-trigger">
										<?php _e("apply all »", "oxygen"); ?>
									</div>
								</div>
								<!-- .oxygen-four-sides-measure-box -->
							</div>
						</div>
					</div>