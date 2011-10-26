<h1>Choose details for this property</h1>

<ul>
	<?php $values = $form->getDefaults() ?>
	<?php $i=0; foreach($form as $label=>$field): $id=uniqid() ?>
		<li id="<?php echo $id ?>">
			<?php $originalDetails = $form->getObject()->getDetails(); ?>
			<?php $det = $form->getDefaults(); ?>
			<?php $det = $det['Detail']['replicated'] ?>
			<?php $photos = $values['Detail']['replicated'][$i++]->getPhotos() ?>
			
			
			<?php if(count($photos)): ?>
				<ul class="photoContainer">
					<?php foreach($photos as $photo): ?>
						<li>
							<?php echo thumbnail('/uploads/'.$photo->getPhoto(), 60, 60) ?>
						</li>
					<?php endforeach ?>
				</ul>
			<?php endif ?>
			
			<?php echo $field ?>
			
			<script type="text/javascript">
				$(function(){
					$("#<?php echo $id ?> .replicableForms > li > ul > li:nth-child(2)").css("padding-right","70px");
				});
			</script>
		</li>
	<?php endforeach ?>
</ul>
