<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
<?php echo $this->addCredentialCondition(sprintf(<<<EOF
<td class="pk-admin-%s pk-admin-list-td-%s">
  [?php echo %s ?]
</td>

EOF
, strtolower($field->getType()), $name, $this->renderField($field)), $field->getConfig()) ?>
<?php endforeach; ?>
