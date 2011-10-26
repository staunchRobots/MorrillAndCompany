<div class="pk-pager-navigation">
		
	[?php if ($pager->getPage() == 1):?]
		<span class="pk-pager-navigation-image pk-pager-navigation-first pk-pager-navigation-disabled">First Page</span>	
	  <span class="pk-pager-navigation-image pk-pager-navigation-previous pk-pager-navigation-disabled">Previous Page</span>
	[?php else: ?]
		<a href="[?php echo url_for('<?php echo $this->getUrlForAction('list') ?>') ?]?page=1" class="pk-pager-navigation-image pk-pager-navigation-first">First Page</a>
  	<a href="[?php echo url_for('<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getPreviousPage() ?]" class="pk-pager-navigation-image pk-pager-navigation-previous">Previous Page</a>
	[?php endif ?]


  [?php foreach ($pager->getLinks() as $page): ?]
    [?php if ($page == $pager->getPage()): ?]
      <span class="pk-page-navigation-number pk-pager-navigation-disabled">[?php echo $page ?]</span>
    [?php else: ?]
      <a href="[?php echo url_for('<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $page ?]" class="pk-page-navigation-number">[?php echo $page ?]</a>
    [?php endif; ?]
  [?php endforeach; ?]


	[?php if ($pager->getPage() == $pager->getLastPage()):?]
	  <span class="pk-pager-navigation-image pk-pager-navigation-next pk-pager-navigation-disabled">Next Page</span>
		<span class="pk-pager-navigation-image pk-pager-navigation-last pk-pager-navigation-disabled">Last Page</span>	
	[?php else: ?]                                                                                                             
	  <a href="[?php echo url_for('<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getNextPage() ?]" class="pk-pager-navigation-image pk-pager-navigation-next">Next Page</a>
  	<a href="[?php echo url_for('<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getLastPage() ?]" class="pk-pager-navigation-image pk-pager-navigation-last">Last Page</a>
	[?php endif ?]                                                                                                             

</div>
 