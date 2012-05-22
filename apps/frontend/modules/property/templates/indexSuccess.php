<?php $sf_response->setTitle('Morrill and Company | Renting and Selling Unique Homes In Baltimore County, Maryland') ?>
<?php $sf_response->addMeta('description', 'Morrill and Company specializes in creating modern, comfortable living spaces in baltimore country, lot development, and unqiue rentals') ?>

<?php slot('bodyId','indexpage') ?>
<?php slot('htmlId','indexpage') ?>

<?php $photos = $property->getPhotos() ?>
<?php $photo = $property->getMainPhoto() ?>

</div>
<?php include_partial('contactModal') ?>

<div id="propertyWrapper">
  <header>
    <a id="logo" href="/"></a>
    
    <nav id="propertiesTypes">
      <ul>
	<li <?php if(!$activeType) echo 'class="active"'; ?>>
	  <?php echo link_to('All', '@property_index') ?>
	</li>
	<?php foreach(Doctrine::getTable('Property')->getStatuses() as $k=>$type): ?>
	  <li <?php if($type==$activeType) echo 'class="active"'; ?>>
	    <?php echo link_to($type, sprintf('@property_index?type=%s', $type)) ?>
	  </li>
	<?php endforeach ?>
	<li><a href="#contactFormWrapper" class="contact">Contact</a></li>
	<!--li class="indicator"><?php echo image_tag('spinner2.gif') ?></li-->
      </ul>
    </nav>
    
    <div class="fb-like" data-href="66.228.36.185:81" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
  </header>

  <!--div class="content_holder">
	  <div class="bg_top">
		  <div class="left_frame_t">&nbsp;</div>
		  <div class="right_frame_t">&nbsp;</div>
	  </div>
	  <div class="main_nav_wrapper">
		  <div class="main_nav">
		  </div>
	  </div>
  </div-->

  <section id="page" class="propertyData">
	  <?php include_component('property', 'list') ?>
  </section>

  <!--div class="footer_wrapper">
	  <div id="footer">
		  <span class="copy">&copy; 2010 Morrill & Co.</span>
		  <ul>
			  <li><a href="#">Terms - </a></li>
			  <li><a href="#">Privacy Policy - </a></li>
			  <li><a href="#">Admin Login</a></li>
		  </ul>
		  <div class="produced">
			  <span>Site produced by</span>
			  <a href="http://morrillwebsites.com" class="small_logo">wild cat</a>
		  </div>
	  </div>
  </div-->

</div>
	
