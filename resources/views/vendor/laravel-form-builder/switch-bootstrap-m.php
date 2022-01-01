<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
        <div class="m-form__group form-group">
    <?php endif; ?>
<?php endif; ?>

<?php if ($showField): ?>
	    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
	        <?= Form::customLabel($name, __(strtolower($options['label'])), 'class=""') ?>
	    <?php endif; ?>
    
        <span class="m-bootstrap-switch bootstrap-switch--brand  m-bootstrap-switch--success">
            <?= Form::checkbox($name, $options['value'], $options['checked'], $options['attr']) ?>
        </span>

    <?php include 'help_block.php' ?>
<?php endif; ?>

<?php include 'errors.php' ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>