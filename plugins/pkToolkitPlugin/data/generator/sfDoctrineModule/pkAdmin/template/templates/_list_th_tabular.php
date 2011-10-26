<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>

	[?php slot('pk-admin.current-header') ?]
	<th class="pk-admin-<?php echo strtolower($field->getType()) ?> pk-admin-list-th-<?php echo $name ?>">
	<?php if ($field->isReal()): ?>
	  [?php if ('<?php echo $name ?>' == $sort[0]): ?]
	    [?php echo link_to(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), '<?php echo $this->getModuleName() ?>/index?sort=<?php echo $name ?>&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?]
	    [?php echo image_tag(sfConfig::get('app_pkAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'pk-admin'), 'title' => __($sort[1], array(), 'pk-admin'))) ?]
	  [?php else: ?]
	    [?php echo link_to(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), '<?php echo $this->getModuleName() ?>/index?sort=<?php echo $name ?>&sort_type=asc') ?]
	  [?php endif; ?]
	<?php else: ?>
	  [?php echo __('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>') ?]
	<?php endif; ?>
	</th>
	[?php end_slot(); ?]

<?php echo $this->addCredentialCondition("[?php include_slot('pk-admin.current-header') ?]", $field->getConfig()) ?>

<?php endforeach; ?>
