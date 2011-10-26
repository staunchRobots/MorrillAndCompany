[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 12482 2008-10-31 11:13:22Z fabien $
 */
class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  public function linkToNew($params)
  {
    return '<li class="pk-admin-action-new">'.link_to(__($params['label'], array(), 'pk_admin'), $this->getUrlForAction('new'), array() ,array("class"=>"pk-btn icon big pk-add")).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="pk-admin-action-edit">'.link_to(__($params['label'], array(), 'pk_admin'), $this->getUrlForAction('edit'), $object, array('class'=>'pk-btn icon icon-only pk-edit')).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="pk-admin-action-delete">'.link_to(__($params['label'], array(), 'pk_admin'), $this->getUrlForAction('delete'), $object, array('class'=>'pk-btn icon-only icon pk-delete','method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'pk_admin') : $params['confirm'])).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="pk-admin-action-list">'.link_to(__($params['label'], array(), 'pk_admin'), $this->getUrlForAction('list'), array(), array('class'=>'pk-btn icon pk-cancel event-default')).'</li>';
  }

  public function linkToSave($object, $params)
  {
    return '<li class="pk-admin-action-save">'.jq_link_to_function(__($params['label'], array(), 'pk_admin'), "$('#pk-admin-form').submit()", array('class'=>'pk-btn pk-save') ).'</li>';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }
    return '<li class="pk-admin-action-save-and-add">'.jq_link_to_function(__($params['label'], array(), 'pk_admin'), '$(this).after("<input type=\"hidden\" name=\"_save_and_add\" value=\"1\" id=\"pk_admin_save_and_add\">");$("#pk-admin-form").submit()', array('class'=>'pk-btn') ).'</li>';
  }

  public function getUrlForAction($action)
  {
    return 'list' == $action ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
}
