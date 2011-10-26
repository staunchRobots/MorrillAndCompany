<?php

class sfWidgetFormRatingStars extends sfWidgetFormChoice
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('multiple', false);
		$this->setOption('expanded', true);

    $this->addOption('maxRating', 5);

		$ratings = array();
		for($i=1,$max=$this->getOption('maxRating');$i<=$max;$i++) $ratings[$i] = $i;
		$this->setOption('choices', $ratings);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
		$id = uniqid();
		$radios = '<div class="radios">'.parent::render($name, $value, $attributes, $errors).'</div>';

		sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
		$radioBox = get_partial("stadium/ratingBox", array(
				'rating'        => $value,
				'maxRating'     => $this->getOption('maxRating'),
				'radioIdPrefix' => $this->generateId($name),
		));

		return '<div class="uiStarRatingWrapper">'.$radios.$radioBox.'</div>';
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('sfWidgetFormRatingStars');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array('sfWidgetFormRatingStars');
  }

}
