<?php $sf_response->setTitle('Morrill and Company | Renting and Selling Unique Homes In Baltimore County, Maryland') ?>
<?php $sf_response->addMeta('description', 'Morrill and Company specializes in creating modern, comfortable living spaces in baltimore country, lot development, and unqiue rentals') ?>

<?php slot('bodyId','indexpage') ?>
<?php slot('htmlId','indexpage') ?>

<?php $photos = $property->getPhotos() ?>
<?php $photo = $property->getMainPhoto() ?>
<?php /* if($photo): ?>
<div style="position:absolute;width:100%;height:100%;overflow:hidden;">
	<img id="siteBg" title="/uploads/<?php echo $photo->getPhoto() ?>" src="" />
<?php endif */ ?>
</div>
<?php include_partial('contactModal', array('property'=>$property)) ?>

<div class="container">
	<div class="main_wrapper1">
		<div class="main_wrapper2">
			<div class="main_wrapper3">
				<div class="header_wrapper">
					<div id="header">
						<h1 class="logo"><a href="/">Morril and company. North baltimore County</a></h1>
						<div class="phone_holder">
							<span>410.357.5488</span>
							<a href="#contactFormWrapper" class="contact">message</a>
							<em>or send us a</em>
						</div>
					</div>
				</div>

				<div class="content_holder">
					<div class="bg_top">
						<div class="left_frame_t">&nbsp;</div>
						<div class="right_frame_t">&nbsp;</div>
					</div>
					<div class="main_nav_wrapper">
						<div class="main_nav">
							<ul>
								<li <?php if(!$activeType) echo 'class="active"'; ?>>
									<?php echo link_to('All', '@property_index') ?>
								</li>
								<?php foreach(Doctrine::getTable('Property')->getStatuses() as $k=>$type): ?>
									<li <?php if($type==$activeType) echo 'class="active"'; ?>>
										<?php echo link_to($type, sprintf('@property_index?type=%s', $type)) ?>
									</li>
								<?php endforeach ?>
								<li class="indicator"><?php echo image_tag('spinner2.gif') ?></li>
							</ul>
							<!--div class="social">
								<ul>
									<li><a href="#" class="facebook">facebook</a></li>
									<li><a href="#" class="twitter">twitter</a></li>
								</ul>
							</div-->
						</div>
					</div>
				</div>
	
				<div id="listContainer">
					<?php include_component('property', 'list') ?>
				</div>

				<div class="footer_wrapper">
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
				</div>
			</div>
		</div>
	</div>
</div>
	
