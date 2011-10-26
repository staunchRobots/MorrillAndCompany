<?php

// A simple inline backtrace that won't crash with an out of
// memory error if the parameters are big objects (as
// debug_print_backtrace sometimes does), clutter the
// browser with huge var_dumps, etc. The trace can be
// collapsed and expanded with a mouse click. Just call
// pkTrace::printTrace() from anywhere. Or call
// pkTrace::trace() to get the trace HTML code as
// a string, which may be useful in contexts where you're building
// something up in a helper.
// 
// Plaintext versions are also available and quite useful, call
// pkTrace::traceText() for a plaintext trace as a string and
// pkTrace::printTraceText() to echo it as text. To log it
// to the Symfony log with 'info' priority, call
// pkTrace::traceLog(). You can then grep for method names. Super useful.
//
// I don't use a javascript toolkit here because this ought to work in sites
// built with any of them. 
//
// 2009-08-10: migrated from pkSimpleBacktraceTracePlugin to reduce plugin count bloat.
// Shortened class name for convenience. Added traceLog().
//
// tom@punkave.com

class pkTrace
{
  static $traceId = 0;
  static public function trace($ignoreCount = 1)
  {
    $result = "";
    self::$traceId++;
    $traceId = "pkTrace" . self::$traceId;
    $traceIdShow = $traceId . "Show";
    $traceIdHide = $traceId . "Hide";
    // Oops, I left this out (fixed 0.6.1)
    // 1.2 (and 1.1?) puts this in JavascriptBase and does not have Javascript
    // except in backwards compatibility mode
    if (SYMFONY_VERSION >= 1.1)
    {
      sfLoader::loadHelpers(array('Tag', 'JavascriptBase'));
    }
    else
    {
      sfLoader::loadHelpers(array('Javascript'));
    }
    $result .= "<div class='pkTrace'>Trace " . 
      link_to_function("&gt;&gt;&gt;", 
        "document.getElementById('$traceId').style.display = 'block'; " .
        "document.getElementById('$traceIdShow').style.display = 'none'; " .
        "document.getElementById('$traceIdHide').style.display = 'inline'",
        array("id" => $traceIdShow)) .
      link_to_function("&lt;&lt;&lt;", 
        "document.getElementById('$traceId').style.display = 'none'; " .
        "document.getElementById('$traceIdHide').style.display = 'none'; " .
        "document.getElementById('$traceIdShow').style.display = 'inline'",
        array("id" => $traceIdHide, "style" => 'display: none'));
    $result .= "</div>";
    $result .= "<pre id='$traceId' style='display: none'>\n";
    $result .= self::traceText($ignoreCount + 1);
    $result .= "</pre>\n";
    return $result;
  }
  static public function printTrace()
  {
    echo(self::trace(2));
  }
  static public function traceText($ignoreCount = 1)
  {
    $trace = debug_backtrace();    
    $count = 0;
    $result = "";
    foreach ($trace as $element)    
    {
      $count++;
      if ($count > $ignoreCount)
      {
        $result .= "Class: " . (isset($element['class']) ? $element['class'] : 'NONE') . " function: " . $element['function'] . " line: " . $lastLine . "\n";
      }
      if (isset($element['line']))
      {
        $lastLine = $element['line'];
      }
      else
      {
        $lastLine = 'NONE';
      }
    }
    return $result;
  }
  static public function printTraceText()
  {
    echo(self::traceText(2));
  }  
  static public function traceLog()
  {
    sfContext::getInstance()->getLogger()->info(self::traceText());
  }
}
