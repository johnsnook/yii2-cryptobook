<?php

namespace johnsnook\cryptobook\controllers;

use johnsnook\cryptobook\models\Book;
use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller {

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
        ];
    }

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReindex($id, $toc) {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->toc = explode(',', $toc);
        $model->save(FALSE);
        return ['success' => true];
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Book();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $key = KeyProtectedByPassword::createRandomPasswordProtectedKey($model->key);
            $model->key = $key->saveToAsciiSafeString();
            if ($model->save()) {
                return $this->redirect(['chapters', 'book_id' => $model->id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionOpen($id, $request) {
        $model = $this->findModel($id);
        $bkSesh = "book-$model->id";

        /** if we already have the key in the session, skip this step */
        $session = Yii::$app->session;
        if ($session->has($bkSesh)) {
            return $this->redirect($request);
        }

        /**
         * They've submitted their passphrase, see if it's valid.  If so, store
         * the unlocked key in a session variable for chapters to use.
         */
        if ($book = Yii::$app->request->post('Book')) {
            $passphrase = $book['passphrase'];
            $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($model->key);
            try {
                $keyObj = $protected_key->unlockKey($passphrase);
                $session->set($bkSesh, $keyObj->saveToAsciiSafeString());
                return $this->redirect($request);
            } catch (WrongKeyOrModifiedCiphertextException $ex) {
                $session->setFlash('warning', "Passphrase is incorrect.  Please enter the correct passphrase. " . $ex->getMessage());
            }
        }

        return $this->render('open', [
                    'model' => $model,
                    'request' => $request
        ]);
    }

    /**
     * check if we have a decrypt key stored in the session.  If not, redirect
     * them to reenter it
     *
     * @param string $id
     * @return mixed
     */
    public static function needKey($id) {
        if (false === Book::getDecryptKey($id)) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                Yii::$app->session->set('chapterPost', $post);
            }
            return static::redirect([
                        'book/open',
                        'id' => $id,
                        'request' => \Yii::$app->request->url
            ]);
        }
        return false;
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (!Yii::$app->session->has("book-$id")) {
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['chapters', 'book_id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        if (!$session->has("book-$id")) {
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * The user is not idle, so refresh the session timeout
     *
     * @return array
     */
    public function actionKeepalive() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
