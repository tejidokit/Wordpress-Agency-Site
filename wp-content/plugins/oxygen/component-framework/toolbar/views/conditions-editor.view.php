
<div class="oxygen-sidebar-code-editor-wrap">
  

  <textarea ui-codemirror="{
        lineNumbers: true,
        mode: 'php',
        type: 'custom-css',
        onLoad : codemirrorLoaded
      }" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['conditions'][iframeScope.component.options[iframeScope.component.active.id]['model']['conditionsIndex']]['logic']"
      ></textarea>

</div>

<div class="oxygen-control-row oxygen-control-row-bottom-bar oxygen-control-row-bottom-bar-code-editor">
  <a href="#" class="oxygen-code-editor-apply"
    ng-click="iframeScope.evalCondition()">
    <?php _e("Apply", "oxygen"); ?>
  </a>
  <a href="#" class="oxygen-code-editor-expand"
    data-collapse="<?php _e("Collapse Editor", "oxygen"); ?>" data-expand="<?php _e("Expand Editor", "oxygen"); ?>"
    ng-click="toggleSidebar()">
    <?php _e("Expand Editor", "oxygen"); ?>
  </a>
</div>