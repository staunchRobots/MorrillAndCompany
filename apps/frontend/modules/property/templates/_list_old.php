<?php $i=0;foreach($pager->getResults() as $p): ++$i; if(!$p->getMainPhoto()) continue; ?>
	<div class="<?php echo ($i % 2) == 0 ? 'black' : 'grey' ?>_box">
		<div class="holder">
			<div class="info_box">
				<address><?php echo $p->getFormattedAddress() ?></address>
				<ul>
					<li class="bed"><a href="#"><?php echo $p->getBedrooms() ?> BR</a></li>                                 
					<li class="bath"><a href="#"><?php echo $p->getBathrooms() ?></a></li>
					<li class="car"><a href="#"><?php echo $p->getGarageSpaces() ?></a></li>

					<li class="cell"><a href="#"><?php echo $p->getLotsSize() ?>a</a></li>
					<li class="cell"><a href="#"><?php echo $p->getSquareFootage() ?>sqft</a></li>
				</ul>
				<span class="price"><?php echo $p->getPrice() ?></span>
				<span class="state"><?php echo $p->getStatus() ?></span>
				
				<h3><?php echo $p->getTagline() ?></h3>
				<p><?php echo $p->getDescription() ?></p>
				<div class="links">
					<?php echo link_to('SEE MORE PHOTOS', '@show?id='.$p->getId()) ?>
					<a href="#contactFormWrapper" class="contact">CONTACT US</a>
				</div>
			</div>
			<?php $path = sfConfig::get('sf_upload_dir').'/'.$p->getMainPhoto()->getPhoto() ?>
			<?php $url = thumbnail_url($path, 640, 500, 60); ?>
			<div class="image_holder"><div class="inside" style="background:url(<?php echo $url ?>) center center;"></div></div>
		</div>
	</div>
	<div class="bg_bottom">&nbsp;</div>
<?php endforeach ?>

<div class="pagination_wrapper"> 
	<div class="pagination_holder">
		<?php include_partial('pager', array('pager'=>$pager,'pagerUrl'=>'@property_index')) ?>
	</div>
</div>
