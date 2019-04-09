<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'css/site.css',
    ];
    public $js = [

    	//'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
    	//'js/vendor/jquery.ui.widget.js',
	    //'https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js',
	    //'https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js',
	    //'https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js',
	    //'js/jquery.iframe-transport.js',
	    'js/jquery.fileupload.js',
	    'js/vendor/jquery.ui.widget.js',
	    'js/jquery.iframe-transport.js',
	    'js/jquery.fileupload.js'
	    //'js/jquery.fileupload-process.js',
	    //'js/jquery.fileupload-image.js',
	    //'js/jquery.fileupload-audio.js',
	    //'js/jquery.fileupload-video.js',
	    //'js/jquery.fileupload-validate.js',
	    //'js/main.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
