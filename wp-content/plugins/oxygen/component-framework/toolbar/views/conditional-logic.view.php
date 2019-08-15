<!-- 
<div class="oxygen-control-row" ng-repeat="item in iframeScope.component.options[iframeScope.component.active.id]['model']['conditions'] track by $index">
	<input type="text" ng-model="item.name"><button ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['conditionsIndex'] = $index; switchTab('advanced', 'conditions-editor');">Edit</button><button ng-click="iframeScope.component.options[iframeScope.component.active.id]['model']['conditions'].splice($index, 1)">Delete</button>
</div>
<button ng-click = "iframeScope.component.options[iframeScope.component.active.id]['model']['conditions'].push({name:''}); iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'conditions');">Add Rule</button> -->


<div class="oxygen-sidebar-code-editor-wrap">
  

  <textarea ui-codemirror="{
        lineNumbers: true,
        mode: 'php',
        type: 'custom-css',
        onLoad : codemirrorLoaded
      }" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['conditions']"
      ></textarea>

</div>

<div class="oxygen-control-row oxygen-control-row-bottom-bar oxygen-control-row-bottom-bar-code-editor">
  <a href="#" class="oxygen-code-editor-apply"
    ng-click="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'conditions', false, false); iframeScope.evalCondition()">
    <?php _e("Apply", "oxygen"); ?>
  </a>
  <a href="#" class="oxygen-code-editor-expand"
    data-collapse="<?php _e("Collapse Editor", "oxygen"); ?>" data-expand="<?php _e("Expand Editor", "oxygen"); ?>"
    ng-click="toggleSidebar()">
    <?php _e("Expand Editor", "oxygen"); ?>
  </a>
</div>