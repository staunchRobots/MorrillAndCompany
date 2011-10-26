<?php $sf_response->setTitle($property->getFormattedAddress().' | '.$property->getTagline()) ?>
<?php $sf_response->addMeta('description', $property->getDescription()) ?>

<?php $photos = $property->getPhotos() ?>
<?php $photo = $property->getMainPhoto() ?>

<?php if($photo): ?>
	<img style="display:none" src="/uploads/<?php echo $photo->getPhoto() ?>"/>
	<img id="siteBg" title="/uploads/<?php echo $photo->getPhoto() ?>" src="" />
<?php endif ?>

<?php // Load all the thumbnails first ?>
<div style="display:none">
  <?php $i=0; foreach($property->getPhotos() as $photo): ?>
	<?php $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto() ?>
	<?php try { $tPath = thumbnail_path($path, 220, 105, 50); } catch(Exception $e) { continue; } ?>
	<?php $url = thumbnail_url($path, 220, 105, 50); ?>
	<?php $size = getimagesize('.'.$tPath); ?>
	<?php echo image_tag($url) ?>
  <?php endforeach ?>
</div>

<?php $imgs = array('icons','label','see-more','player','watchvideo','photos','see-more') ?>
<?php foreach($imgs as $p): ?><?php echo image_tag($p, 'style=display:none') ?><?php endforeach ?>



<header class="uiBox">
	<div class="bg"></div>
	<div class="content">
		<a id="logo" href="/"></a>
		
		<a id="backToIndex" class="backToIndex" href="/"></a>
		<a id="contactUs" class="contact" href="#contactFormWrapper" rel="prettyPhoto"></a>
		<a id="fbShare" type="icon_link" name="fb_share" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2F4603prospect.com%2F&t=4603%20prospect&src=sp"></a>
		
		<!--script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script-->

		<hgroup>
			<div class="intro">
				<h2>
					<?php echo $property->getTagline() ?>
					<?php echo $property->getFormattedAddress() ?>
				</h2>
				<ul>
					<li class="bed"><a href="#"><?php echo $property->getBedrooms() ?> BR</a></li>
					<li class="bath"><a href="#"><?php echo $property->getBathrooms() ?> and <?php echo $property->getHalfBathrooms() ?> half</a></li>

					<li class="car"><a href="#"><?php echo $property->getGarageSpaces() ?></a></li>
					<li class="cell"><a href="#"><?php echo $property->getLotsSize() ?>a</a></li>
					<li class="cell"><a href="#"><?php echo $property->getSquareFootage() ?>sqft</a></li>
					<li class="price"><a href="#"><?php echo $property->getPrice() ?></a></li>
				</ul>
			</div>
		</hgroup>
		<details>
			<nav class="details">
				<ul class="options">
					<?php if($property->getHaveSeeMore()): ?>
						<li><a class="details" href="#contactFormWrapper" alt="Hide details">Show details</a></li>
					<?php endif ?>
					<!--li><a class="contact" href="#contactFormWrapper" rel="prettyPhoto">Contact owner</a></li-->
				</ul>
			</nav>
		</details>
	</div>
</header>

<section id="page">

	<details id="propertyDetails">
		<?php include_partial('contactModal', array('property'=>$property)) ?>
		<aside id="uiGoogleMap">
			<!-- header><h2>Property on map</h2></header-->
			<section class="formWrapper">
				<div id="map_canvas" style="overflow:hidden; display:block; width:500px; height:500px; position: relative;"></div>
				<input id="formattedAddress" value="<?php echo $property->getFormattedAddress() ?>" type="hidden"/>
				<div style="display:none;"></div>
			</section>
		</aside>
	</details>

	<section id="propertyData">
		<nav id="sections">
			<ul>
				<?php $details = $property->getDetails() ?>
				<?php $y = 0; foreach($details as $detail): //  class="active" ?>
					<li <?php echo ++$y == 1 ? 'class="active"' : ''?>>
						<a href="#">
							<span class="content"><?php echo $detail->getName() ?></span>
							<span class="bg"></span>
						</a>
						<span style="display: none">
							<ul class="photos">
								<?php $photos = $detail->getPhotos() ?>
								<?php $photo = $photos[0] ?>
								<?php if($detail->getMovieUrl()): ?>
									<?php $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto() ?>
									<?php $tPath = thumbnail_path($path, 310, 270, 60) ?>
									<?php $tUrl = thumbnail_url($path, 310, 270) ?>
									<?php $size = getimagesize('.'.$tPath); ?>
									<li class="movie"
										><a class="arrow" href="<?php echo $detail->getMovieUrl() ?>"></a>
										<?php echo image_tag($tUrl, sprintf('width=%s height=%s', $size[0], $size[1])) ?>
									></li>
								<?php endif ?>
								
								<?php $i=0;foreach($photos as $photo): if(++$i>4)break;?>
									<?php try { ?>
										<?php $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto() ?>
										<?php $tPath = thumbnail_path($path, 220, 105, 60) ?>
										<?php $url = thumbnail_url($path, 220, 105, 60); ?>
										<?php $size = getimagesize('.'.$tPath); ?>
										<li
											><a href="/uploads/<?php echo $photo->getPhoto() ?>" style="max-width: 120px; display: block; height: 68px; overflow: hidden;"
												><?php echo image_tag($url, 'height=125px') ?>
											</a
										></li>
									<?php } catch(Exception $e) {} ?>
								<?php endforeach ?>
							</ul>
							<p class="name"><?php echo strip_tags(html_entity_decode($detail->getName())) ?></p>
							<p class="details"><?php echo strip_tags(html_entity_decode($detail->getDescription())) ?></p>
							<ul class="features">
								<?php $i=0; foreach($detail->getFeatures() as $feature):?>
									<li class="<?php echo ++$i%2==0?'even':'' ?>">
										<?php echo $feature->getName() ?>
									</li>
								<?php endforeach ?>
							</ul>
						</span>
					</li>
				<?php endforeach ?>
			</ul>
		</nav>

		<details id="sectionData">
			<div class="bg"> </div>
			<div class="content">
				<span class="close_button"></span>
				<h1><?php echo strip_tags(html_entity_decode($details[0]->getName())) ?></h1>
				<section class="features">
					<header>
						<h2>Features</h2>
					</header>
					<ul>
						<?php $i=0; foreach($details[0]->getFeatures() as $feature):?>
							<li class="<?php echo ++$i%2==0?'even':'' ?>">
								<?php echo $feature->getName() ?>
							</li>
						<?php endforeach ?>
					</ul>
				</section>
				<article class="description">
					<h2>Description</h2>
					<p>
						<?php echo strip_tags(html_entity_decode($details[0]->getDescription())) ?>
					</p>
				</article>
				<nav class="options">
					<ul>
						<li class="contact"><a class="contact" href="#contactFormWrapper">Contact owner</a></li>
					</ul>
				</nav>
			</div>
		</details>
	</section>
</section>

<footer class="uiBox">

  <section id="thumbsGallery">
    <div class="arrLeft"></div>
      <div class="itemsHolder">
	    <ul class="items">
	    
		  <?php $i=0; foreach($property->getPhotos() as $photo): // if(++$i > 12) break; ?>
		    <?php $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto() ?>
		    <?php try { $tPath = thumbnail_path($path, 220, 105, 50); } catch(Exception $e) { continue; } ?>
	 	    <?php $url = thumbnail_url($path, 220, 105, 50); ?>
	  	    <?php $size = getimagesize('.'.$tPath); ?>
		    <li
		 	  ><a href="/uploads/<?php echo $photo->getPhoto() ?>"><?php echo image_tag($url) ?></a
		    ></li>
		  <?php endforeach ?>
	
		  <!--li class="logo"></li-->
	    </ul>
	  </div>
    <div class="arrRight"></div>
  </section>

	<!--section id="uiFooter" class="uiBox">
		<div class="bg"></div>
		<div class="content">
			WildCat is currently offering a free license of REAL ESTATE showrooms like this, would you like one?
		</div>
	</section-->

</footer>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16993226-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
