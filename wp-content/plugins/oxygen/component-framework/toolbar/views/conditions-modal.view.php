
<div ng-if="dialogForms['ifCondition']" id='ct-modal-if-conditions' class='ct-global-conditions-add-modal ct-global-conditions-choose-operator oxygen-data-dialog'>
            <h1>Conditions
              
              <svg class="oxygen-close-icon"
          ng-click="hideDialogWindow()"><use xlink:href="#oxy-icon-cross"></use></svg>
            </h1>

        
          
            <div class='oxygen-condition-builder-condition'
              ng-repeat="(index, condition) in iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']">
                
                <div class='oxygen-control'>
                    <div class="oxygen-select oxygen-select-box-wrapper"
                        ng-click="toggleOxygenSelectBox($event)">
                        <div class="oxygen-select-box">
                            <div class="oxygen-select-box-current oxy-tooltip">
                                <span ng-if="condition.name != 'ZZOXYVSBDYNAMIC'">{{condition.name}}</span>
                                <span ng-if="condition.name == 'ZZOXYVSBDYNAMIC'">{{condition.oxycode}}</span>
                                <span class="placeholder-text" ng-if="!condition.name"><?php _e("Choose Condition...","oxygen");?></span>
                                <div class="oxy-tooltip-text" ng-if="condition.name">
                                    <span ng-if="condition.name != 'ZZOXYVSBDYNAMIC'">{{condition.name}}</span>
                                <span ng-if="condition.name == 'ZZOXYVSBDYNAMIC'">{{condition.oxycode}}</span>
                                </div>
                            </div>
                            <div class="oxygen-select-box-dropdown"></div>
                        </div>
                        <div class="oxygen-select-box-options">

                            
                            <div class="oxygen-conditions-group-container"
                                ng-repeat="(groupname, group) in iframeScope.globalConditionsGrouped">
                                <div class="oxygen-conditions-group-label">{{groupname}}</div>
                                <div ng-repeat="item in group">
                                    <div class="oxygen-select-box-option"
                                    ng-if="item.name == 'ZZOXYVSBDYNAMIC'"
                                    ng-click="conditionsDialogOptions.selectedIndex = index"
                                    ctdynamicdata="" noshadow="1" backbutton=true data="iframeScope.dynamicShortcodesContentMode" callback="assignOxyCodeToCondition">
                                    <?php _e("Dynamic Data","oxygen");?>
                                    </div>

                                    <div class="oxygen-select-box-option"
                                        ng-if="item.name != 'ZZOXYVSBDYNAMIC'"
                                        ng-click="

                                        iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['operator'] = (iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']!==item.name) ? 0:iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['operator'];

                                        iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['value'] = (iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']!==item.name) ? '':iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['value'];

                                        iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']=item.name; 
                                                    
                                                    iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"
                                        >
                                        {{item.name}}
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- .oxygen-select-box-options -->
                    </div>
                    <!-- .oxygen-select.oxygen-select-box-wrapper -->
                </div>
            





                <div class='oxygen-control'>
                    <div class="oxygen-select oxygen-select-box-wrapper"
                        ng-click="toggleOxygenSelectBox($event)">
                        <div class="oxygen-select-box">
                            <div class="oxygen-select-box-current oxy-tooltip">
                               {{iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['operators'][condition.operator]}}
                               <span class="placeholder-text" ng-if="condition.operator === null">{{condition.operator}}<?php _e("==","oxygen");?></span>
                               <div class="oxy-tooltip-text"  ng-if="condition.operator !== null">{{iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['operators'][condition.operator]}}</div>
                            </div>
                            <div class="oxygen-select-box-dropdown"></div>
                        </div>
                        <div class="oxygen-select-box-options">

                            <div class="oxygen-select-box-option"
                                ng-repeat="operator in iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['operators']" ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['operator']=$index; 
                        
                         iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"
                                >
                                    {{operator}}
                            </div>
                            
                        </div>
                        <!-- .oxygen-select-box-options -->
                    </div>
                    <!-- .oxygen-select.oxygen-select-box-wrapper -->
                </div>









                <div class='oxygen-control'>
                    <div class="oxygen-select oxygen-select-box-wrapper"
                        ng-click="toggleOxygenSelectBox($event)">
                        <div class="oxygen-select-box">
                            <div class="oxygen-select-box-current oxy-tooltip">
                               <input
                                ng-if="iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['custom']"
                                class="global-conditions-custom-value"
                                type="text" value="" placeholder="{{iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['placeholder']?iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['placeholder']:'Custom Value...'}}" spellcheck="false" 
                                ng-model-options='{ debounce: 1000 }'
                                ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['value']" class="ng-valid ng-dirty ng-valid-parse ng-touched"

                                ng-change="iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); 
                                        evalGlobalConditions()"
                                 />

                                <div ng-if="iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['custom']" 
                                ng-click="conditionsDialogOptions.selectedIndex = index"
                                class="oxygen-dynamic-data-browse" ctdynamicdata noshadow="1" backbutton=true data="iframeScope.dynamicShortcodesContentMode" callback="assignOxyCodeToConditionValue">data</div>

                                <span
                                ng-if="!iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['custom']">{{iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['keys'] && iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options'][condition.value]?iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options'][condition.value]:condition.value}}</span>
                                <span class="placeholder-text" ng-if="condition.value && !iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['custom']===null"><?php _e("Value...","oxygen");?></span>

                                <div class="oxy-tooltip-text" ng-if="condition.value">{{iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['keys'] && iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options'][condition.value]?iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options'][condition.value]:condition.value}}</div>
                            </div>
                            <div class="oxygen-select-box-dropdown" ng-if="!iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['custom'] && (iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options'].length || iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['keys'])"></div>
                        </div>
                        <div class="oxygen-select-box-options">
                            
                            <div class="oxygen-select-box-option global-conditions-value-item"
                                ng-repeat="(key, value) in iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['options']">
                                <span
                                ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['value']= (iframeScope.globalConditions[iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'][index]['name']]['values']['keys']?key:value); 

                                        iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); 
                                        evalGlobalConditions();"
                                >
                                    {{value}}
                                </span>
                            </div>
                            
                        </div>
                        <!-- .oxygen-select-box-options -->
                    </div>
                    <!-- .oxygen-select.oxygen-select-box-wrapper -->
                </div>

              <div class='oxygen-condition-builder-condition-delete'
                ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].splice(index, 1); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"
                >
                <img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/toolbar-icons/cancel-circle.svg' />
              </div>

            </div>

          
        
          
        <div class='oxygen-condition-builder-add-condition' ng-if="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].length > 0">
              <a ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] = iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] || []; iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].push({name: '', operator: null, value: ''}); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"><?php _e("Add Condition","oxygen");?></a>
        </div> 

        <div class="oxygen-add-button"
            ng-if="!iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].length"

            ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] = iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'] || []; iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions'].push({name: '', operator: null, value: ''}); iframeScope.setOptionModel('globalconditions', iframeScope.component.options[iframeScope.component.active.id]['model']['globalconditions']); evalGlobalConditions()"

            >
                <span><?php _e("Add your first condition","oxygen");?></span>
        </div>
</div>