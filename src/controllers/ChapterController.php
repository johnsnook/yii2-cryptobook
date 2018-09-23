<?php

namespace johnsnook\cryptobook\controllers;

use johnsnook\cryptobook\models\Book;
use johnsnook\cryptobook\models\Chapter;
use johnsnook\cryptobook\behaviors\IdleBehavior;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ChapterController implements the CRUD actions for Chapter model.
 */
class ChapterController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'idle' => ['class' => IdleBehavior::className()]
        ];
    }

    public function init() {
        parent::init();
        $this->view->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
    }

    /**
     *
     * @return array The post array, either from a session or a direct request
     */
    private static function getPost() {
        $session = Yii::$app->session;
        if ($session->has('chapterPost')) {
            $from = 'Session';
            $post = $session->get('chapterPost');
            $session->remove('chapterPost');
        } else {
            $from = 'Post';
            $post = Yii::$app->request->post();
        }
//        \johnsnook\cryptobook\d::d([$from, $post]);

        return $post;
    }

    /**
     * Lists all Chapter models.
     * @return mixed
     */
    public function actionIndex($book_id) {
        static::prepPage($this->view);
        if ($answer = BookController::needKey($book_id)) {
            return $answer;
        } else {
            $book = Book::findOne($book_id);
            $models = Chapter::find()
                    ->where("book_id=:id", [':id' => $book_id])
                    ->all();
            $models = ArrayHelper::index($models, 'id');
            return $this->render('index', [
                        'book' => $book,
                        'chapters' => $models,
            ]);
        }
    }

    /**
     * Displays a single Chapter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        static::prepPage($this->view);

        $model = $this->findModel($id);
        if ($answer = BookController::needKey($model->book_id)) {
            return $answer;
        } else {
            $sections = ArrayHelper::index($model->sections, 'id');
            return $this->render('view', [
                        'chapter' => $model,
                        'sections' => $sections,
            ]);
        }
    }

    /**
     * Creates a new Chapter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $book_id
     * @return mixed
     */
    public function actionCreate($book_id) {
        static::prepPage($this->view);

        if ($answer = BookController::needKey($book_id)) {
            return $answer;
        } else {
            $post = static::getPost();
            $model = new Chapter();
            if ($model->load($post)) {
                if ($model->save()) {
                    return $this->redirect(['index', 'book_id' => $model->book_id]);
                }
            }
            $model->book_id = $book_id;
            return $this->render('create', [
                        'book_id' => $book_id,
                        'model' => $model,
            ]);
        }
    }

    public function actionReindex($id, $toc) {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->toc = explode(',', $toc);
        $model->save(FALSE);
        return ['success' => true];
    }

    /**
     * Updates an existing Chapter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        static::prepPage($this->view);

        $model = $this->findModel($id);
        $post = static::getPost();
        $request = Yii::$app->request;
//        \johnsnook\cryptobook\d::d($post);
        if ($answer = BookController::needKey($model->book_id)) {
            return $answer;
        } else {
            if ($request->isAjax && $request->isPost) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->load($post) && $model->save()) {
                    return ['success' => true];
                }
            }
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Chapter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $book_id = $model->book_id;
        if ($answer = BookController::needKey($book_id)) {
            return $answer;
        } else {
            $model->delete();

            return $this->redirect(['index', 'book_id' => $book_id]);
        }
    }

    /**
     * Finds the Chapter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chapter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Chapter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
