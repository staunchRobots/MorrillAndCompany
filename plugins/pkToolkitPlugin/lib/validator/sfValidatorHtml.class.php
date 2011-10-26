<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorHtml validates an HTML string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Alex Gilbert <alex@punkave.com>
 * @version    SVN: $Id: sfValidatorHtml.class.php 12641 2008-11-04 18:22:00Z fabien $
 */
class sfValidatorHtml extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('allowed_tags', 'Your field contains unsupported HTML tags.');

    $this->addOption('allowed_tags', '<h3><h4><h5><h6><blockquote><p><a><ul><ol><nl><li><b><i><strong><em><strike><code><hr><br><div><table><thead><caption><tbody><tr><th><td>');
    $this->addOption('strip', true);
    
    unset($options['allowed_tags']);
    unset($options['strip']);
    
    parent::configure($options, $messages);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = (string) $value;

    if ($this->getOption('strip'))
    {
      $clean = pkHtml::simplify($clean, $this->getOption('allowed_tags'));
    }
    else
    {
      // Currently, this validator only supports stripping bad HTML, it doesn't throw an 
      // error message if bad HTML is present. Need to refactor some regex code from
      // the pkHtml class to run that check here.
    }
    
    $clean = parent::doClean($clean);
    
    return $clean;
  }
}
