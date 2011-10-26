[?php use_helper('I18N', 'Date', 'jQuery') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<div id="pk-admin-container" class="[?php echo $sf_params->get('module') ?]">

  [?php include_partial('<?php echo $this->getModuleName() ?>/list_bar', array('filters' => $filters)) ?]
  	
	<div id="pk-admin-subnav" class="subnav">
		
		<ul class="pk-controls pk-admin-action-controls">
			<?php if ($this->configuration->hasFilterForm()): ?>
  			<li class="filters">[?php echo jq_link_to_function("Filters", "$('#pk-admin-filters-container').slideToggle()" ,array('class' => 'pk-btn icon pk-settings', 'title'=>'Filter Data')) ?]</li>
			<?php endif; ?>
				<li>[?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('pager' => $pager)) ?]</li>
		</ul>
  </div>

	<div id="pk-admin-content" class="main">
		<ul id="pk-admin-list-actions" class="pk-controls pk-admin-action-controls">
  		[?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]		
		</ul>
		<?php if ($this->configuration->hasFilterForm()): ?>
		  [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration)) ?]
		<?php endif; ?>

		[?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]
		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
			<form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post" id="pk-admin-batch-form">
		<?php endif; ?>
		[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]
				<ul class="pk-admin-actions">
		      [?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
		    </ul>
		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
		  </form>
		<?php endif; ?>
	</div>

  <div id="pk-admin-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager)) ?]
  </div>

</div>
