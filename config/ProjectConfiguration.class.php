<?php

require_once dirname(__FILE__).'/../../symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    sfConfig::set('sf_web_dir', dirname(__FILE__).'/..');
    sfConfig::set('sf_upload_dir', dirname(__FILE__).'/../uploads');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfAdminDashPlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('csDoctrineActAsSortablePlugin');
    $this->enablePlugins('sfThumbnailPlugin');
    $this->enablePlugins('pkToolkitPlugin');

    $this->enablePlugins('sfWidgetFormInputSWFUploadPlugin');

    $this->getEventDispatcher()->connect(
      'form.validation_error',
      array('BaseForm', 'listenToValidationError')
    );

    $this->getEventDispatcher()->connect(
      'template.filter_parameters',
       array($this, 'filterTemplateParameters')
    );

    $this->getEventDispatcher()->connect(
      'form.post_configure',
      array('BaseForm', 'postConfigure')
    );
    $this->enablePlugins('sfFormExtraPlugin');
  }

  /**
   * Automatyczne dołączanie stylów i skryptów dla formularzy
   */
  public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    $response = sfContext::getInstance()->getResponse();
    foreach ($parameters as $parameter)
    {
      if ($parameter instanceof sfForm)
      {
      	foreach ($parameter->getJavascripts() as $javascript)
        {
          $response->addJavascript($javascript);
        }
        foreach ($parameter->getStylesheets() as $stylesheet => $media)
        {
					if(is_numeric($stylesheet)) {
						$stylesheet = $media;
						$media = 'screen';
					}
          $response->addStylesheet($stylesheet, "", array("media" => $media));
        }
      }
    }
    return $parameters;
  }

}
