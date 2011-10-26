<?php if ($listActions = $this->configuration->getValue('list.batch_actions')): ?>
<li class="pk-admin-batch-actions-choice">
  <select name="batch_action">
    <option value="">[?php echo __('Choose an action', array(), 'pk-admin') ?]</option>
<?php foreach ((array) $listActions as $action => $params): ?>
    <?php echo $this->addCredentialCondition('<option value="'.$action.'">[?php echo __(\''.$params['label'].'\', array(), \'pk-admin\') ?]</option>', $params) ?>
<?php endforeach; ?>
  </select>
<?php $form = new sfForm(); if ($form->isCSRFProtected()): ?>
  <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
<?php endif; ?>
	[?php echo jq_link_to_function('Go', '$("#pk-admin-batch-form").submit();', array('class' => 'pk-btn', )) ?]
</li>
<?php endif; ?>
