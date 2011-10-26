<?php $propertyId = isset($property) && is_object($property) ? $property->getId() : '' ?>
<?php
	$tabs = array(
		1 => 'General',
		2 => 'Details',
		4 => 'Upload',
		5 => 'Management',
	)
?>
<ol class="tabs">
	<?php foreach($tabs as $i=>$tabName): ?>
		<li<?php if($i == $active) echo ' class="active"'?>>
			<?php echo link_to_if($propertyId || ($active > $i), $tabName, sprintf('@property_wizard?step=%d&id=%d', $i, $propertyId)) ?>
		</li>
	<?php endforeach ?>
</ol>
