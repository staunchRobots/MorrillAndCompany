<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

  <?php $i=0;foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
	<li class="tab<?php if(++$i == 1) echo ' active'?>" id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
		<h2 class="legend"><?php echo $name ?></h2>
		<div class="viewport">
		    <?php include_partial('property/form_field', array(
			      'name'       => $name,
			      'attributes' => $field->getConfig('attributes', array()),
			      'label'      => $field->getConfig('label'),
			      'help'       => $field->getConfig('help'),
			      'form'       => $form,
			      'field'      => $field,
			      'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
			    )) ?>
		</div>
	</li>
  <?php endforeach; ?>
