<?php use_stylesheets_for_form($form)?>
<?php use_javascripts_for_form($form)?>

<div class="sf_admin_form">
  <?php echo form_tag_for($form, '@stadiums') ?>
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
    
	<?php $fieldsList = $configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') ?>
	
	<div class="tabbedPane">
		<ol class="tabs">
			<li class="active" id="tab_step1"><a href="#">Property data</a></li>
			<li id="tab_step2"><a href="#">Photo</a></li>
		</ol>
		
		<ul class="navigation">
			<li><input type="button" class="prevTab" value="&lt; prev" /></li>
			<li><input type="button" class="nextTab" value="next &gt; " /></li>
		</ul>
		
		<ol class="viewports">
			<li class="tab active" id="tab_step1">
				<?php echo $form['step1'] ?>
			</li>
			<li class="tab" id="tab_step2">
				<h1>Create new configuration or choose existing one</h1>
				
				<div class="mainSelect">
					<?php echo $form['step2']['configuration'] ?>
				</div>
				
				<?php echo $form['step2']['ConfigurationForm'] ?>
			</li>
			<li class="tab" id="tab_step3">
				<ul class="boxModules">
					<?php $maxInRow = 4 ?>
					<?php $i=0;foreach($form['step3'] as $k=>$embeddedForm):++$i ?>
						<?php $rendered = $embeddedForm->__toString() ?>
						<li<?php if($i%$maxInRow == 0) echo ' style="clear: right;"'?>
						   <?php if(($i-1)%$maxInRow == 0) echo ' style="clear: left;"'?>>
							<h2><?php echo $k?> <input type="button" value="Add new!" class="replicate" /></h2>
							<ul class="replicableForms">
								<li><?php echo $rendered ?></li>
							</ul>
							<input type="hidden" value="<?php echo htmlentities($rendered) ?>" name="replicate_pattern" />
						</li>
					<?php endforeach ?>
				</ul>
			</li>
		</ol>
		
		<ul class="navigation">
			<li><input type="button" class="prevTab" value="Prev!" /></li>
			<li><input type="button" class="nextTab" value="Next!" /></li>
		</ul>
		
	</div>

    <?php include_partial('stadiums/form_actions', array('stadiums' => $stadiums, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
