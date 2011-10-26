<?php # Not really a new pager - just a well-styled partial for use with ?>
<?php # sfPager implementations (i.e. sfDoctrinePager). ?>
<?php # Pass $pager and $pagerUrl. $pagerUrl can have other parameters in ?>
<?php # the query string already. This partial will add the page parameter ?>
<?php # as appropriate. ?>

<?php // Turns out we don't want to display the pager when there's only one page after all ?>
<?php if ($pager->haveToPaginate()): ?>
  <ul class="pager">

    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <li class="active"><a href="#"><?php echo $page ?></a></span>
      <?php else: ?>
        <li><a href="<?php echo url_for(pkUrl::addParams($pagerUrl, array("page" => $page))) ?>"><?php echo $page ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    
  	<?php if ($pager->getPage() < $pager->getLastPage()):?>
	  <li class="pager_arrow"><a href="<?php echo url_for(pkUrl::addParams($pagerUrl, array("page" => $pager->getNextPage()))) ?>">next</a></li>
  	<?php endif ?>
  </ul>
<?php endif ?>