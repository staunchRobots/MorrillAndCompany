<?php $sf_response->setTitle($property->getFormattedAddress().' | '.$property->getTagline()) ?>
<?php $sf_response->addMeta('description', $property->getDescription()) ?>

<?php $photos = $property->getPhotos() ?>
<?php $photo = $property->getMainPhoto() ?>
<?php include_partial('contactModal', compact($property)) ?>
<div id="propertyWrapper">

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
    <a id="logo" href="/"></a>
    
    <!--nav id="propertiesList">
      <span><?php echo $property->getTagline() ?></span>
      <a href="#"></a>
      <ul>
	<?php foreach($properties as $p): ?>
	  <li<?php if($p->getId() == $property->getId()) echo ' class="active"' ?>>
	    <a href="/<?php echo $p->getId() ?>">
	      <?php echo $p->getTagline() ?>
	    </a>
	  </li>
	<?php endforeach ?>
      </u>
    </nav-->

    <nav id="siteNav">
      <ul>
	<li><a href="/">Home</a></li>
	<li><a class="contact" href="#contactFormWrapper" >Contact</a></li>
      </ul>
    </nav>

  </header>

  <section id="page" class="propertyData uiBox">
    <?php if($photo): ?>
      <div id="mainImage">
	<img src="<?php
	  //echo '/uploads/'.$photo->getPhoto()
	  $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto();

	  echo thumbnail_url($path, 625, 420, 50);
	?>" />
      </div>
    <?php endif ?>

    <nav id="thumbsGallery">
      <ul>
	<?php $i=0; foreach($property->getPhotos() as $photo): if(++$i > 15) break; ?>
	  <?php $path = sfConfig::get('sf_upload_dir').'/'.$photo->getPhoto() ?>
	  <?php try { $tPath = thumbnail_path($path, 220, 105, 50); } catch(Exception $e) { continue; } ?>
	  <?php $url = thumbnail_url($path, 220, 105, 50); ?>
	  <?php $size = getimagesize('.'.$tPath); ?>
	  <li><a href="<?php echo thumbnail_url($path, 625, 420, 50) // echo '/uploads/'.$photo->getPhoto() ?>"><?php echo image_tag($url) ?></a></li>
	<?php endforeach ?>
      </ul>
    </nav>
  </section>

  <footer class="uiBox">
    <section class="newsletter">
      <input type="text" name="email" value="" />
<script type="text/javascript">
$(function(){
  $(".newsletter input[name=email]").tbHinter({
    text: "Enter email to receive new property notifications"
  });
});
</script>
      <input type="submit" value="Submit" />
    </section>
    <section class="copy"><a href="http://staunchrobots.com">a staunchRobot contraption</a></section>
  </footer>

</div>
