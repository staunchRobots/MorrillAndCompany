<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormJQueryDate represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryDate.class.php 12875 2008-11-10 12:22:33Z fabien $
 */
class sfWidgetFormJQueryTime extends sfWidgetFormTime
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:   The image path to represent the widget (false by default)
   *  * config:  A JavaScript array that configures the JQuery time widget
   *  * culture: The user culture
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');

    parent::configure($options, $attributes);

    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget (sometimes already an array with hour and minute keys)
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $prefix = $this->generateId($name);

    $image = '';
    if (false !== $this->getOption('image'))
    {
      // TODO: clock widget handling
    }
    $hourid = $this->generateId($name.'[hour]');
    $minid = $this->generateId($name.'[minute]');
    
    $s = '<span style="display: none">' . parent::render($name, $value, $attributes, $errors) . '</span>';
    $val = '';
    if (is_array($value))
    {
      if (strlen($value['hour']) && strlen($value['minute']))
      {
        $val = htmlspecialchars(pkDate::time(sprintf("%02d:%02d:00", $value['hour'], $value['minute']), false));
      }
    }
    elseif (strlen($value))
    {
      $val = htmlspecialchars(pkDate::time($value, false), ENT_QUOTES);
    }
    $s .= "<input type='text' name='pk-ignored' id='$prefix-ui' value='$val' class='" . (isset($attributes['class']) ? $attributes['class'] : '') . "'><img id='$prefix-ui-trigger' class='ui-timepicker-trigger' src='/pkToolkitPlugin/images/pk-icon-time.png'/>";
    $s .= <<<EOM
<script>
$(function() { 
  var hour;
  var min;
  var times = [ ];
  for (thour = 0; (thour < 24); thour++)
  {
    // Starting with 8am makes more sense for typical clients
    var hour = (thour + 8) % 24;
    for (min = 0; (min < 60); min += 30)
    {
      times.push(prettyTime(hour, min));
    }
  }
  $('#$prefix-ui').autocomplete(times, { minChars: 0, selectFirst: false, max: 100 });
  // Double click on focus pops up autocomplete immediately
  $('#$prefix-ui').focus(function() { $(this).click(); $(this).click() } ).next().click(function(event){
		event.preventDefault();
		$(this).prev().focus();
	});
  $('#$prefix-ui').blur(function() {
    var val = $(this).val();
    var components = val.match(/(\d\d?)(:\d\d)?\s*(am|pm)?/i);
    if (components)
    {
      var hour = components[1];
      var min = components[2];
      if (min)
      {
        min = min.substr(1);
      }
      if (!min)
      {
        min = '00';
      }
      if (min < 10)
      {
        min = '0' + Math.floor(min);
      }
      var ampm = components[3] ? components[3].toUpperCase() : false;
      if (!ampm)
      {
        if (hour >= 8)
        {
          ampm = 'AM';
        }
        else
        {
          ampm = 'PM';
        }
      }
      var formal = hour + ':' + min + ampm;
      $(this).val(formal);
      if ((ampm === 'AM') && (hour == 12))
      {
        hour = 0;
      }
      if (ampm === 'PM')
      {
        // Careful: force numeric
        hour = Math.floor(hour) + 12;
      }
      $('#$hourid').val(hour);
      $('#$minid').val(min);
      // Something to bind to in other places
      $(this).trigger('pkTimeUpdated');
    }
    else
    {
      if (val.length)
      {
        alert("The time must be in hh:mm format, followed by AM or PM. Hint: click on the typeahead suggestions.");
        $('#$prefix-ui').focus();
      }
    }
  });
  function prettyTime(hour, min)
  {
    var ampm = 'AM';
    phour = hour;
    if (hour >= 12)
    {
      ampm = 'PM';
    }
    if (hour >= 13)
    {
      phour -= 12;
    }
    if (phour == 0)
    {
      phour = 12;
    }
    pmin = min;
    if (min < 10)
    {
      pmin = '0' + Math.floor(min);
    }
    return phour + ':' + pmin + ampm;
  }

	// General useability stuff that the original date widget was lacking because it was made by robots and not actual human beings
	$('#$prefix-ui-trigger').attr('title','Set A Time').hover(function(){
		$(this).fadeTo(0,.5);
	},function(){
		$(this).fadeTo(0,1);
	});
});
</script>
EOM
;
    return $s;
  }
}
