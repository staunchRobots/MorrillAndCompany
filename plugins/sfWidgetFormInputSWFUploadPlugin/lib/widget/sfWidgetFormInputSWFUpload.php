<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
sfApplicationConfiguration::getActive()->loadHelpers('Url');
/**
 * Description of sfWidgetFormInputSWFUpload
 *
 * @author joshi
 */
class sfWidgetFormInputSWFUpload extends sfWidgetFormInputFile
{
  /**
   * Instance counter
   *
   * @var integer
   */
  protected static $INSTANCE_COUNT = 0;

  protected function iniSize2Bytes($ini_size)
  {
    if (preg_match('#^([0-9]+?)([gmk])$#i', $ini_size, $tokens))
    {
      $unit=null; $size_val=null;
      isset($tokens[1])&&$size_val  = $tokens[1];
      isset($tokens[2])&&$unit      = $tokens[2];
      if($size_val && $unit)
      {
        switch(strtolower($unit))
        {
          case 'k':
            return $size_val * 1024 . 'B';
          case 'm':
            return $size_val * 1024 * 1024 . 'B';
          case 'g':
            return $size_val * 1024 * 1024 * 1024 . 'B';
        }
      }
    }
    else
    {
      return $ini_size . 'B';
    }
  }

  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('reset_on_dialog', true);

    $this->addOption('custom_javascripts', array());

    $this->addOption('prevent_form_submit', true);

    $this->addOption('collapse_queue_on_init', true);

    $this->addOption('send_serialized_values', true);
    $this->addOption('require_yui', false);

    $this->addOption('swfupload_upload_url', $_SERVER['REQUEST_URI']);
    $this->addOption('swfupload_post_name', null);
    $this->addOption('swfupload_post_params', '');

    $this->addOption('swfupload_file_types', '*.jpg;*.jpeg;*.gif;*.png');
    $this->addOption('swfupload_file_types_description', 'Web images');

    $this->addOption('swfupload_file_size_limit', ini_get('upload_max_filesize'));
    $this->addOption('swfupload_file_upload_limit', 0);
    $this->addOption('swfupload_file_queue_limit', 0);

    $this->addOption('swfupload_flash_url',     public_path('/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/swfupload/swfupload.swf'));

    $this->addOption('swfupload_css_path',      public_path('/sfWidgetFormInputSWFUploadPlugin/css/swfupload.css'));
    $this->addOption('swfupload_js_path',       public_path('/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/swfupload/swfupload.js'));
    $this->addOption('swfupload_handler_path',  public_path('/sfWidgetFormInputSWFUploadPlugin/js/swfupload-widget-handler.js'));
    $this->addOption('swfupload_plugins_dir',   public_path('/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/plugins'));
    $this->addOption('swfupload_button_image_url', null);

    $this->addOption('swfupload_button_width', 100);
    $this->addOption('swfupload_button_height', 18);
    $this->addOption('swfupload_button_text', '');
    $this->addOption('swfupload_button_text_style', '');
    $this->addOption('swfupload_button_text_left_padding', 0);
    $this->addOption('swfupload_button_text_top_padding', 0);
    $this->addOption('swfupload_button_disabled', 'false');
    $this->addOption('swfupload_button_cursor', 'SWFUpload.CURSOR.ARROW');
    $this->addOption('swfupload_button_window_mode', 'SWFUpload.WINDOW_MODE.TRANSPARENT');
    $this->addOption('swfupload_button_action', 'SWFUpload.BUTTON_ACTION.SELECT_FILES');

    $this->addOption('swfupload_swfupload_loaded_handler', 'swfu_widget.handlers.onLoad');
    $this->addOption('swfupload_file_dialog_start_handler', 'swfu_widget.handlers.onFileDialogStart');
    $this->addOption('swfupload_file_queued_handler', 'swfu_widget.handlers.onFileQueued');
    $this->addOption('swfupload_file_queue_error_handler', 'swfu_widget.handlers.onFileQueueError');
    $this->addOption('swfupload_file_dialog_complete_handler', 'swfu_widget.handlers.onFileDialogComplete');
    $this->addOption('swfupload_upload_start_handler', 'swfu_widget.handlers.onUploadStart');
    $this->addOption('swfupload_upload_progress_handler', 'swfu_widget.handlers.onUploadProgress');
    $this->addOption('swfupload_upload_error_handler', 'swfu_widget.handlers.onUploadError');
    $this->addOption('swfupload_upload_success_handler', 'swfu_widget.handlers.onUploadSuccess');
    $this->addOption('swfupload_upload_complete_handler', 'swfu_widget.handlers.onUploadComplete');
    $this->addOption('swfupload_queue_complete_handler', 'swfu_widget.handlers.onQueueComplete');
    $this->addOption('swfupload_swfupload_pre_load_handler', 'swfu_widget.handlers.onPreLoad');
    $this->addOption('swfupload_swfupload_load_failed_handler', 'swfu_widget.handlers.onLoadFailed');
    $this->addOption('swfupload_minimum_flash_version', '9.0.28');
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array(
      $this->getOption('swfupload_css_path') => 'all'
    );
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array("http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/animation/animation-min.js");
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    self::$INSTANCE_COUNT++;
    //*.jpg;*.gif
    $extensions = is_array($this->getOption('swfupload_file_types')) ?
      implode(';', $this->getOption('swfupload_file_types')):
      $this->getOption('swfupload_file_types');

    $widget_id  = $this->getAttribute('id') ? $this->getAttribute('id') : $this->generateId($name);
    $button_id  = $widget_id . "_swfupload_target";

    $inpform = parent::render($name, $value, $attributes, $errors);
    $output = <<<EOF
      $inpform
      <div id="uiElements" style="display:inline;">
			<div id="uploaderContainer">
				<div id="uploaderOverlay" style="position:absolute; z-index:2"></div>
				<div id="selectFilesLink" style="z-index:1"><a id="selectLink" href="#">Select Files</a></div>
			</div>
			<div id="uploadFilesLink"><a id="uploadLink" onClick="upload(); return false;" href="#">Upload Files</a></div>
	  </div>
	  <div id="simUploads">
	  		Number of simultaneous uploads:
			<select id="simulUploads">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
		
		<div id="dataTableContainer"></div>
		<!-- Dependencies -->
		<script src="http://yui.yahooapis.com/2.8.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
		<script src="http://yui.yahooapis.com/2.8.1/build/element/element-min.js"></script>
		 
		<!-- Source files -->
		<script src="http://yui.yahooapis.com/2.8.1/build/uploader/uploader-min.js"></script>
				
      <script type="text/javascript">
        //<![CDATA[
        YAHOO.util.Event.onDOMReady(function () { 
			var uiLayer = YAHOO.util.Dom.getRegion('selectLink');
			var overlay = YAHOO.util.Dom.get('uploaderOverlay');
			YAHOO.util.Dom.setStyle(overlay, 'width', uiLayer.right-uiLayer.left + "px");
			YAHOO.util.Dom.setStyle(overlay, 'height', uiLayer.bottom-uiLayer.top + "px");
		});
		YAHOO.widget.Uploader.SWFURL = "http://yui.yahooapis.com/2.8.1/build/uploader/assets/uploader.swf";
		var uploader = new YAHOO.widget.Uploader( "uploaderOverlay" );
		
		uploader.addListener('contentReady', handleContentReady);
		uploader.addListener('fileSelect', onFileSelect)
		uploader.addListener('uploadStart', onUploadStart);
		uploader.addListener('uploadProgress', onUploadProgress);
		uploader.addListener('uploadCancel', onUploadCancel);
		uploader.addListener('uploadComplete', onUploadComplete);
		uploader.addListener('uploadCompleteData', onUploadResponse);
		uploader.addListener('uploadError', onUploadError);
	    uploader.addListener('rollOver', handleRollOver);
	    uploader.addListener('rollOut', handleRollOut);
	    uploader.addListener('click', handleClick);
		uploader.addListener('mouseDown', handleMouseDown);
		uploader.addListener('mouseUp', handleMouseUp);	
	
		
		function handleRollOver () {
			YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'color', "#FFFFFF");
			YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'background-color', "#000000");
		}
	
		function handleRollOut () {
			YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'color', "#0000CC");
			YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'background-color', "#FFFFFF");
		}
		
		function handleMouseDown () {}
		function handleMouseUp () {}
		function handleClick () { }
		
		function handleContentReady () {
		    // Allows the uploader to send log messages to trace, as well as to YAHOO.log
			uploader.setAllowLogging(true);
			
			// Allows multiple file selection in "Browse" dialog.
			uploader.setAllowMultipleFiles(true);
			
			// New set of file filters.
			var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"},
			                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});
			                   
			// Apply new set of file filters to the uploader.
			uploader.setFileFilters(ff);
		}
		var fileList;
		
		function onFileSelect(event) {
			if('fileList' in event && event.fileList != null) {
				fileList = event.fileList;
				createDataTable(fileList);
			}
		}

		function createDataTable(entries) {
		  rowCounter = 0;
		  this.fileIdHash = {};
		  this.dataArr = [];
		  for(var i in entries) {
		     var entry = entries[i];
			 entry["progress"] = "<div style='height:5px;width:100px;background-color:#CCC;'></div>";
		     dataArr.unshift(entry);
		  }
		
		  for (var j = 0; j < dataArr.length; j++) {
		    this.fileIdHash[dataArr[j].id] = j;
		  }
		
		    var myColumnDefs = [
		        {key:"name", label: "File Name", sortable:false},
		     	{key:"size", label: "Size", sortable:false},
		     	{key:"progress", label: "Upload progress", sortable:false}
		    ];
		
		  this.myDataSource = new YAHOO.util.DataSource(dataArr);
		  this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
		  this.myDataSource.responseSchema = {
		      fields: ["id","name","created","modified","type", "size", "progress"]
		  };
		
		  this.singleSelectDataTable = new YAHOO.widget.DataTable("dataTableContainer",
		           myColumnDefs, this.myDataSource, {
		               caption:"Files To Upload",
		               selectionMode:"single"
		           });
		}
		
		function upload() {
			if (fileList != null) {
				uploader.setSimUploadLimit(parseInt(document.getElementById("simulUploads").value));
				uploader.uploadAll("http://www.yswfblog.com/upload/upload_simple.php");
			}
		}
			
		function onUploadProgress(event) {
			rowNum = fileIdHash[event["id"]];
			prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));
			progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:" + prog + "px;'></div></div>";
			singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});	
		}
		function onUploadComplete(event) {
			rowNum = fileIdHash[event["id"]];
			prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));
			progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:100px;'></div></div>";
			singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});
		}
		function onUploadStart(event) {	}
		function onUploadError(event) {}
		function onUploadCancel(event) {}
		function onUploadResponse(event) {}
        //]]>
      </script>
EOF;
    return $output;
  }
}