<div class="customize-control customize-control-select" id="customize-control-<?php echo $control['field']; ?>">
    <label for="<?php echo $control['field']; ?>" class="customize-control-title"><?php echo $control['label']; ?> <?php if ( isset( $control['tooltip'] ) ) : ?><i class="fa fa-question-circle" aria-hidden="true" data-pointer><span><?php echo $control['tooltip']; ?></span></i><?php endif; ?></label>
    <select name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>" data-attribute="<?php echo $control['field']; ?>" data-pointer-target>
    <?php foreach ( $control['options'] as $option => $label ) : ?>
        <option value="<?php echo $option; ?>" <% if ( '<?php echo $option; ?>' === <?php echo $control['field']; ?> ) { %>selected="selected"<% } %>><?php echo $label; ?></option>
    <?php endforeach; ?>
    </select>
</div>