[?php if ($sf_user->hasFlash('notice')): ?]
  <div class="pk-admin-flashes notice">[?php echo __($sf_user->getFlash('notice'), array(), 'pk-admin') ?]</div><br class="c"/>
[?php endif; ?]

[?php if ($sf_user->hasFlash('error')): ?]
  <div class="pk-admin-flashes error">[?php echo __($sf_user->getFlash('error'), array(), 'pk-admin') ?]</div><br class="c"/>
[?php endif; ?]
