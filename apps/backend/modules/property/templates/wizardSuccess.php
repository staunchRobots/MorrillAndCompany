<h1><?php
echo get_slot('pageHeader',
		isset($property) && $property ?
			sprintf('Edit data for property %s', $property->getFormattedAddress())
				: 'Property wizard') ?></h1>
<div class="sf_admin_form">
  <form method="post" enctype="multipart/form-data" action="">
	<div class="tabbedPane"> 
		<?php include_partial('tabPanel', array('active'=>$step, 'property'=>$property))?>
		
		<ul class="navigation">
			<li><input type="submit" class="nextTab" value="Save &gt;"/></li>
		</ul>
		
		<div class="viewport">
			<?php if ($form->hasGlobalErrors()): ?>
			  <?php echo $form->renderGlobalErrors() ?>
			<?php endif; ?>

			<?php include_partial(sprintf('step%d', $step), array('form'=>$form, 'property'=>$property)) ?>
		</div>

		<ul class="navigation">
			<li><input type="submit" class="nextTab" value="Save &gt;"/></li>
		</ul>
	</div>
  </form>
</div>
