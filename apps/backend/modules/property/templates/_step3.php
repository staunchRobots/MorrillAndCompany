<h1>Choose features for this property</h1>

<ul class="multipleReplicableContainer">
	<?php $ef = $form->getEmbeddedForms() ?>
	<?php $details = $form->getObject()->getDetails(); $details2 = array() ?>
	<?php foreach($details as $d) $details2['Detail: '.$d->getName()] = $d ?>
	<?php foreach($form as $label=>$field): ?>
		<?php if(!$details2[$label]->getHasFeatures()) continue; ?>
		<li><?php echo $field ?></li>
	<?php endforeach ?>
</ul>

<?php 
/*
 * <h1>Choose features for this property</h1>

<ul class="multipleReplicableContainer">
	<?php $ef = $form->getEmbeddedForms() ?>
	<?php foreach($form as $label=>$field): ?>
		<?php $eForm = $ef[$label]; $defaults = $eForm->getDefaults(); $replicableDefaults = $defaults['replicated'] ?>
		<?php $id = 'noPropFor'.$label?>
		<li class="replicableSchemaContainer">
			<h2><?php echo $label ?></h2>
			<input type="checkbox" class="emptySchema" id="<?php echo $id ?>" <?php
				if( (count($form->getObject()->getFeatures()) > 0 && count($replicableDefaults) == 0) )
					echo 'checked="true"'?> />
			<label for="<?php echo $id ?>">No features for this detail</label>
			<?php echo $field ?>
		</li>
	<?php endforeach ?>
</ul>

*/