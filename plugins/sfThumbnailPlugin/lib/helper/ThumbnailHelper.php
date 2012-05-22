<?php

function thumbnail_path($path, $maxWidth, $maxHeight, $quality=50, $params=null)
{
	$path = ($path{0} == '/' ? '' : '/') . $path;
	
	$maxWidth = (int) $maxWidth;
	$maxHeight = (int) $maxHeight;

	$fileName = @end(explode('/', $path));

	$thumbsDir = sfConfig::get('sf_web_dir').'/images/thumbs';
	if(!file_exists($thumbsDir)) {
		mkdir($thumbsDir);
		chmod($thumbsDir, 0777);
	}
	
	$thumbName = $fileName.'-'.$maxWidth.'x'.$maxHeight.'.jpg';
	$thumbPath = '/images/thumbs/'.$thumbName;

	$webDir = sfConfig::get('sf_web_dir');
	$sourcePath = $webDir.str_replace($webDir, '', $path);
	$destPath = sfConfig::get('sf_web_dir').$thumbPath;
	$fileName = @end(explode('/', $path));

	if(!file_exists($destPath)) {
	   try {
		if(substr($path, 0, 9) == '/uploads/')
		  $path = sfConfig::get('sf_web_dir').$path;
		$img = new sfImage($path);
		$img->thumbnail($maxWidth, $maxHeight, 'center');
		$img->setQuality($quality);
		$img->saveAs($destPath);
/*
		$t = new sfThumbnail($maxWidth, $maxHeight, $quality);
		$t->loadFile($sourcePath);
		$t->save($destPath, 'image/jpeg'); 
*/
	   } catch (Exception $e) {
		die($e->getMessage());
	   }
	}

	return $thumbPath;
}

function thumbnail_url($path, $maxWidth, $maxHeight, $params=null)
{
	return url_for(thumbnail_path($path, $maxWidth, $maxHeight, $params));
}

/**
 * Tworzy "W locie" miniaturki obrazków i je cachuje, zwraca
 * HTML'owy tag img odwołujący się do gotowej miniaturki
 * 
 * @param string $path Ścieżka obrazka do pomniejszenia
 * @param int $maxWidth Maksymalna szerokość
 * @param int $maxHeight Maksymala wysokość
 * @param mixed $params Parametry przekazywane do image_tag
 */

function thumbnail($path, $maxWidth, $maxHeight, $params=null)
{
	return image_tag(thumbnail_url($path, $maxWidth, $maxHeight, $params));
}
