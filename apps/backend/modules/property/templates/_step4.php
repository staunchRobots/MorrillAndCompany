<h1>Import photos from Flickr</h1>

<ul class="multipleReplicableContainer">
	<?php $details = $form->getObject()->getDetails() ?>
	<?php foreach($details as $d): $id=uniqid('a')?>
		<?php if(!$d->getHasPhotos()) continue; ?>
		<li>
			<label for="setHeader<?php echo $id ?>"><?php echo $d->getName() ?></label>
			<div class="setUrl">
				<label for="setUrl<?php echo $id ?>">Set URL</label>
				<input type="text" name="setUrl" id="setUrl<?php echo $id ?>" value="" style="width:350px;border:1px solid #cacaca"/>
				<input type="button" value="Import photos" />
				<span class="indicator"></span>
				<span class="error" style="color:#f00;display: none;">URL is incorrect or set is empty</span>
			</div>
			<ul class="images">
			</ul>
			<input type="hidden" name="<?php echo 'property[detail]['.$d->getId().']'?>" />
		</li>
	<?php endforeach ?>
</ul>

<script type="text/javascript">
$(function(){
	$(".multipleReplicableContainer > li")
		.each(function(){
			container = $(this);
			$('input[type=button]', container)
				.click(
					(function(container) {
						return function(e) {
							
							$('[type=button]', container).hide();
							$('.indicator', container).fadeIn().css("display","inline-block");
							
							setUrl = $(this).parent().find('input[type=text]').val();
							if(setUrl.substr(-1) == '/')
							{
								setUrl = setUrl.substr(0, setUrl.length-1);
							}
							parts = setUrl.split('/');
							setId = parts[parts.length-1];
							if(setId.indexOf('-') != -1) { setId = setId.substr(setId.indexOf('-')); }
							$.ajax({
								url: '<?php echo url_for('@import_set?id=0000&url=aaaa') ?>'
											.replace(/0000/, setId)
											.replace(/aaaa/, setUrl
													.replace(/:/g,  "_colon_")
										            .split("/").join("_slash_")
										    ),
								error: function(response) { throwError(container); },
								success: function(response) {
									$('.setUrl', container).fadeOut();
									list = $('.images', container);

									images = eval('('+response+')');

									donedone = false;
									for(i in images)
									{
										donedone = true;
										setTimeout((function(list,images,i){
											return function() {
												elem = $('<li><img src="'+images[i]+'"/></li>');
												list.append(elem);
												elem.hide()
													.fadeIn();
												setDraggable($("img", elem));
												if(i == images.length-1) setDroppable();
											};
										})(list,images,i), i*50);
									}
									if(!donedone) throwError(container);
								}
							});
						};
					})(container)
				);
		});


	function setDraggable(img)
	{
		img
	        .bind( "dragstart", function( event ){
	            var $drag = $( this ), $proxy = $drag.clone();
	            $drag.addClass("outline");
	            return $proxy.appendTo( document.body ).addClass("ghost");
	        })
	    	.bind( "drag", function( event ){
	            $( event.dragProxy ).css({
	                    left: event.offsetX,
	                    top: event.offsetY
	            });
	        })
		.bind( "dragend", function( event ){
	            $( event.dragProxy ).fadeOut( "normal", function(){ $( this ).remove(); });
	            if ( !event.dropTarget && $(this).parent().is(".drop") ){ $('#nodrop').append( this ); }
	            $( this ).removeClass("outline");
     		});
	}
	function setDroppable()
	{
		$(".multipleReplicableContainer > li")
			.unbind("dropstart")
			.unbind("drop")
			.unbind("dropend")
		    .bind( "dropstart", function( event ){
		        $( this ).addClass("active");
		     })
			.bind( "drop", function( event ){
			    $( '.images', this ).append( $(event.dragTarget).parent() );
			})
			.bind( "dropend", function( event ){
                            lis = $(".multipleReplicableContainer > li");
                            for(var u=0,max=lis.length;u<max;u++) {
                                refreshList($(lis[u]));
                            }
			    $( this ).removeClass("active");
			});
	}

	function throwError(container)
	{
		$('[type=button]', container).show();
		$('.indicator', container).fadeOut().css("display","none");
		$('.error', container).fadeIn().delay(4000).fadeOut();
	}
	function refreshList(li) {
                 imgs = $("li.active img", li);
                 srcs = [];

                 for(i in imgs) if(imgs[i].src) srcs[srcs.length] = imgs[i].src;

                 message = $("input[type=hidden]", li);
		 v = (srcs.join(',')+",");
		 v = v.substr(0, v.length-1)
		 n = message.attr("name");
		 if(message.val() == v) return;
		 message.remove();
		 // console.log(v);
		 htmlcode = '<input name="'+n+'" value="'+v+'" type="hidden"/>';
		 // console.log(htmlcode);
		 li.append(htmlcode);
	}
	$(".multipleReplicableContainer .images li")
		.live('click',
			function() {
				if($(this).hasClass("active"))
				{
					$(this).removeClass("active");
				}
				else
				{
					$(this).addClass("active");
				}
				refreshList($(this).parent().parent());
			}
		);
	
});
</script>
