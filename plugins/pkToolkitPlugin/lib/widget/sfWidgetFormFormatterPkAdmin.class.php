<?php

class sfWidgetFormSchemaFormatterPkAdmin extends sfWidgetFormSchemaFormatter 
{
  protected
    $rowFormat = "<div class=\"pk-form-row\">\n  %label%\n  <div class=\"pk-form-field\">%field%</div> <div class='pk-form-error'>%error%</div>\n %help%%hidden_fields%\n</div>\n",
    $errorRowFormat = '%errors%',
    $helpFormat = '<div class="pk-form-help-text">%help%</div>',
    $decoratorFormat ="<div class=\"pk-admin-form-container\">\n %content%\n</div>";
}
