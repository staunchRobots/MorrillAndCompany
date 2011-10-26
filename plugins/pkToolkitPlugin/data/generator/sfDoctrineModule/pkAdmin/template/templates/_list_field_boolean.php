[?php if ($value): ?]
  [?php echo image_tag(sfConfig::get('app_pkAdmin_web_dir').'/images/tick.png', array('alt' => __('Checked', array(), 'pk-admin'), 'title' => __('Checked', array(), 'pk-admin'))) ?]
[?php else: ?]
  &nbsp;
[?php endif; ?]
