<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
//use app\models\UploadHandler;

class UploadForm extends Model
{
	/**
	 * @var UploadedFile
	 */
	public $imageFile;

	public function rules()
	{
		return [
			//[['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, png'],
		];
	}

	public function upload()
	{
		if ($this->validate()) {
			//require('../fileupload/server/php/UploadHandler.php');
			$upload_handler = new UploadHandler();
			$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
			return 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
		} else {
			return false;
		}
	}
}