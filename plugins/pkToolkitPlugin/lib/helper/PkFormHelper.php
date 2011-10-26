<?php

use_helper('Form');

// This wrapper for textarea_tag serves two purposes:
//
// 1. The Symfony textarea_tag allows you to specify a custom
// FCK .js config file via the 'config' option, but doesn't
// have support for an application-wide one, which is good to have
// because customizing fckconfig.js creates maintenance problems
// when FCK is upgraded. This version loads 
// web/js/fckextraconfig.js from the app level if it exists
// and there is no explicit config option passed to the helper.
//
// 2. Symfony contains an unfortunate "fix" to enable the old
// Symfony 1.0 fillin helper to see the linked hidden field as a
// type="text" field. This breaks any text with newlines in it in
// Safari and Chrome. We fix that here. This has also been fixed
// in Symfony 1.2.6 (1.2.7?) but keeping the fix here does no harm
// and increases portability. 
//
// http://trac.symfony-project.com/ticket/732

function pk_textarea_tag($name, $value, $options)
{
  if (isset($options['rich']) && (strtolower($options['rich']) === 'fck'))
  {
    if (!isset($options['config']))
    {
      if (file_exists(sfConfig::get('sf_web_dir') . '/js/fckextraconfig.js'))
      {
        $options['config'] = '/js/fckextraconfig.js'; 
      }
    }
  }
  $result = textarea_tag($name, $value, $options);
  if (isset($options['rich']) && $options['rich'])
  {
    $result = preg_replace('/type="text"/', 'type="hidden"',
      $result, 1);
  }
  return $result;
}

