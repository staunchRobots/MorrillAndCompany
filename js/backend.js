
// Obsługa domyślnej wartości pól formularza
// Po kliknięciu wartość znika, po stracie focusu jeśli pole jest puste,
// wartośc wraca na miejsce
$.fn.clearOnFocus = function() {
	$(this).each(function(){
		t = $(this);
		if(t.attr("title").length == 0 || t.attr("title") == null) return;

		t
			.val( t.attr("title") )
			.focus(function(){
				t = $(this);
				if(t.val() == t.attr("title")) t.val("");
				
				// Opera focus bug
				if(t.attr("reloadingFocus") == "t")
				{
					t.attr("reloadingFocus", "f");
				} else {
					t.attr("reloadingFocus", "t");
					t.focus();
				}
			})
			.blur(function() {
				t = $(this);
				if(t.val() == "") t.val( t.attr("title") );
			});

	});
};

/**
 * Serializes collection of inputs into nice object
 * 
 * @returns object
 */
$.fn.serializeInputs = function() {
	inputs = $(this);

	result = {};
	for(i=0,max=inputs.length;i<max;i++)
	{
		inp = $(inputs[i]);
		name = inp.attr("name");
		
		if(!name) continue;
		if((inp.attr("type") == "checkbox" ||
				inp.attr("type") == "checkbox") && !inp.is(":checked")) continue;
		result[name] = inp.val();
	}
	return result;

};

$.fn.urlify = function() {
	request = [];
	
	data = $(this).serializeInputs();
	for (i in data)
	{
		request[request.length] = i+"="+data[i];
	}
	
	return request.join("&");
}


String.prototype.replaceAll = function(stringToFind,stringToReplace){
    var temp = this;
    var index = temp.indexOf(stringToFind);
    while(index != -1){
        temp = temp.replace(stringToFind,stringToReplace);
        index = temp.indexOf(stringToFind);
    }
    return temp;
}

$(function(){
	// Quick&dirty - deadline today... If will be given a chance,
	// will turn this into jQuery plugin
	
	$.extend($.expr[':'], {
	    focused: function(elem) { return elem.hasFocus; }
	});

		$('.replicableSchemaContainer input[type=checkbox].emptySchema')
			.change(function(){
				input = $(this);
				replicable = $('ul', input.parent());
				if(input.is(":checked")) {
					replicable.fadeOut();
				} else {
					replicable.fadeIn();
				}
			})
			.change();
	// Replicable forms {{{
		$('.replicableSchema .replicate').each(function(){
			button = $(this);
			
			button.click(function(){
				parent = $(this).parent();

				// Container to append results to
				formsContainer = parent.find('> .replicableForms');
				shownForms = parent.find('> .replicableForms > li');

				// Pattern to replicate
				index = shownForms.length;
				pattern = parent.find('> input[name=replicate_pattern]').val();		
				pattern = pattern.replace(/\*js\*/g, index);

				newRow = $("<li>"+pattern+'<input type="button" value="Delete" class="kamikaze"/></li>');
				
				formsContainer.append(newRow);
				$('input,select', newRow).get(0).focus();
			});
			
			form = $(button.parents().filter("form").get(0));
			form.submit((function(button) {
				return function(e) {
					if(window.lastKeyPressed != 13) return true;
					if(
						!button.parent().find('input, select').filter(function() {
							return this == document.activeElement;
						}).length
						||
						button.parent().find('.replicableSchema input, .replicableSchema select').filter(function() {
							return this == document.activeElement;
						}).length) return true;
					button.click();
					e.preventDefault();
				};
			})(button));
		});
		
		$(window).keydown(function(e) { window.lastKeyPressed = e.keyCode; });
		$('.kamikaze').live('click', function(){ $(this).parent().remove(); });
	// }}}

});
