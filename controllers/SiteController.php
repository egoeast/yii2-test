<?php

namespace app\controllers;

use app\models\UploadHandler;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\WebM;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadForm;
use Faker;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

    	//VarDumper::dump($this);
    	//die();
	    //\Kint::dump($this);
	    //$faker = Faker\Factory::create();
	    //dump($this);
	    //dump($faker->address);
	    //dump($faker->url);
	    //dump($faker->dayOfWeek());
	    //dump($this);
	    //dd($this);
	    $model = new UploadForm();

	    if (Yii::$app->request->isPost) {
		    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
		    if ($path = $model->upload()) {
			    // file is uploaded successfully
			    // Загрузка штампа и фото, для которого применяется водяной знак (называется штамп или печать)
			   /* $stamp = imagecreatefrompng('uploads/logo-list.png');
			    $stamp = imagerotate($stamp, 30, 100, false);
			    $im = imagecreatefromjpeg($path);

				// Установка полей для штампа и получение высоты/ширины штампа
			    $marge_right = 10;
			    $marge_bottom = 10;
			    $sx = imagesx($stamp);
			    $sy = imagesy($stamp);

				// Копирование изображения штампа на фотографию с помощью смещения края
				// и ширины фотографии для расчета позиционирования штампа.
			    imagecopy($im, $stamp, $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

				// Вывод и освобождение памяти
			    //header('Content-type: image/png');
			    imagepng($im, $path);
			    imagedestroy($im);*/

/*
			    $filename = $path;
			    $rotang = 20; // Rotation angle
			    $source = imagecreatefrompng($filename) or die('Error opening file '.$filename);
			    imagealphablending($source, false);
			    imagesavealpha($source, true);

			    $rotation = imagerotate($source, $rotang, imageColorAllocateAlpha($source, 0, 0, 0, 127));
			    imagealphablending($rotation, false);
			    imagesavealpha($rotation, true);

			    header('Content-type: image/png');
			    imagepng($rotation);
			    imagedestroy($source);
			    imagedestroy($rotation);*/



			   /* $ffmpeg = FFMpeg::create();
			    $video = $ffmpeg->open($path);
			    $video
				    ->filters()
				    ->resize(new Dimension(320, 240))
				    ->synchronize();
			    $video
				    ->frame(TimeCode::fromSeconds(10))
				    ->save('upload/frame.jpg');
			    $video
				    //->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
				    //->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
				    ->save(new WebM(), 'uploads/export-webm.webm');*/

			    return $this->render('index', ['model' => $model, 'path' => $path]);
		    }
	    }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionUpload()
    {
	    $this->layout = false;

    	return $this->render('upload');
    }

	public function beforeAction($action) {
		$this->enableCsrfValidation = ($action->id !== "uploading"); // <-- here
		return parent::beforeAction($action);
	}

    public function actionUploading()
    {

	    if (empty($_FILES) || $_FILES['file']['error']) {
		    die('{"OK": 0, "info": "Failed to move uploaded file."}');
	    }

	    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

	    $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
	    $filePath = Yii::getAlias('@webroot'). '/uploads/'.$fileName;


// Open temp file
	    $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	    if ($out) {
		    // Read binary input stream and append it to temp file
		    $in = @fopen($_FILES['file']['tmp_name'], "rb");

		    if ($in) {
			    while ($buff = fread($in, 4096))
				    fwrite($out, $buff);
		    } else
			    die('{"OK": 0, "info": "Failed to open input stream."}');

		    @fclose($in);
		    @fclose($out);

		    @unlink($_FILES['file']['tmp_name']);
	    } else
		    die('{"OK": 0, "info": "Failed to open output stream."}');


// Check if file has been uploaded
	    if (!$chunks || $chunk == $chunks - 1) {
		    // Strip the temp .part suffix off
		    rename("{$filePath}.part", $filePath);
	    }

	    die('{"OK": 1, "info": "Upload successful."}');


	    /*  if (empty($_FILES) || $_FILES["file"]["error"]) {
			  die('{"OK": 0}');
		  }

		  $fileName = $_FILES["file"]["name"];
		  move_uploaded_file($_FILES["file"]["tmp_name"], Yii::getAlias('@webroot'). '/uploads/'. $fileName);

		  die('{"OK": 1}');*/

	    //Yii::$app->response->format = Response::FORMAT_JSON;
	    /*$upload_handler = new UploadHandler([
		    'upload_dir' => Yii::getAlias('@webroot') .'/uploads/',
	    	'accept_file_types' => '/\.(mp4|ogg|ogv|webm)$/i',
		    ]);*/
	    //return $upload_handler;

    }
}
