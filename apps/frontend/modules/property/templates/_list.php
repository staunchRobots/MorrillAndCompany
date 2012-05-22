<?php $i=0;foreach($pager->getResults() as $p): ++$i; if(!$p->getMainPhoto()) continue; ?>
  <div class="<?php echo ($i % 2) == 0 ? 'black' : 'grey' ?>_box">
    <div class="holder">
      <div class="info_box">
	<h3><?php echo $p->getTagline() ?></h3>
	<p><?php echo $p->getDescription() ?></p>

	<div class="links">
	  <?php echo link_to('Explore this property', '@show?id='.$p->getId()) ?>
	  <!--a href="#contactFormWrapper" class="contact">CONTACT US</a-->
	</div>

	<address><?php echo $p->getFormattedAddress() ?></address>
	<ul>
	  <li class="bed"><a href="#">Bedrooms: <?php echo $p->getBedrooms() ?></a></li>                                 
	  <li class="bath"><a href="#">Bathrooms: <?php echo $p->getBathrooms() ?></a></li>
	  <li class="car"><a href="#">Garage: <?php echo $p->getGarageSpaces() ?></a></li>

	  <li class="cell"><a href="#">Acres: <?php echo $p->getLotsSize() ?>a</a></li>
	  <li class="cell"><a href="#">Sqft: <?php echo $p->getSquareFootage() ?>sqft</a></li>
	  <li class="cell"><a href="#">Price: <?php echo $p->getPrice() ?></a></li>
	</ul>
      </div>
      <?php $path = sfConfig::get('sf_upload_dir').'/'.$p->getMainPhoto()->getPhoto() ?>
      <?php $url = thumbnail_url($path, 510, 216, 60); ?>
      <div class="image_holder"><div class="inside" style="background:url(<?php echo $url ?>) center center;"></div></div>
    </div>
  </div>
  <!--div class="bg_bottom">&nbsp;</div-->
<?php endforeach ?>

<!--div class="pagination_wrapper"> 
	<div class="pagination_holder">
		<?php include_partial('pager', array('pager'=>$pager,'pagerUrl'=>'@property_index')) ?>
	</div>
</div-->
