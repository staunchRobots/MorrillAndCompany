<h1>Manage photos for this property</h1>

<input type="checkbox" id="check_for_delete" name="check_all" style="margin:30px 0 0 850px;"/> <label for="check_for_delete">Check all</label>
<ul class="photoManagementForm">
	<?php foreach($form as $l1=>$e1): $concreteFormId = "$l1"; $inputId = "property_{$l1}_order" ?>
		<li><h2><?php echo Doctrine::getTable('Detail')->findOneById($l1)->getName() ; //die(var_dump($e1)) ?></h2>
			<ul id="<?php echo $concreteFormId?>">
				<?php echo $form[$l1]['order'] ?>
				<?php foreach($e1 as $l2=>$e2): if($l2 == 'order') continue; ?>
					<li id="<?php echo $concreteFormId.'_'.$l2 ?>">
						<span class="handle">HANDLE!</span>
						<?php // die(var_dump(''.$e2));
						  $data = $e2.'';
						  $imgRegexp = '#<img src="([^"]+)" />#';
						  preg_match_all($imgRegexp, $data, $matches);
						  $imgPath = $matches[1][0];
						  $e2 = preg_replace($imgRegexp, thumbnail($imgPath, 100, 100, 'class=preview'), $data);
						  echo $e2;
						?>
					</li>
				<?php endforeach ?>
			</ul><script type="text/javascript">
			$(function() {

				$("#<?php echo $concreteFormId ?>").sortable({ 
					handle : '.handle', 
					update : function () {
						var order = $('#<?php echo $concreteFormId ?>').sortable('serialize');
						$("#<?php echo $inputId ?>").val(order);
					} 
				});
			
			});
			</script></li>
	<?php endforeach ?>
</ul>
<script type="text/javascript">
$(function() {

	$('input[type=checkbox][name*=is_main_photo]').change(function(e) {
		t = $(this);
		checked = t.is(":checked");
		$('input[type=checkbox][name*=is_main_photo]').removeAttr("checked");
		if(checked) t.attr("checked", true);
	});

	$("#check_for_delete").change(function(e) {

		boxes = $("input[type=checkbox][name*=photo_delete]");
		if($(this).is(":checked")) { boxes.attr("checked", "true") } else { boxes.removeAttr("checked"); }

	});

});
</script>
		
