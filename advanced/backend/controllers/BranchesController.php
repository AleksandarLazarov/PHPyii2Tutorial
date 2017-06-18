<?php

namespace backend\controllers;

use Yii;
use backend\models\Branches;
use backend\models\BranchesSearch;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BranchesController implements the CRUD actions for Branches model.
 */
class BranchesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['create', 'update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Branches models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branches model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Branches model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
//        var_dump(Yii::$app->user->id);
//        die();
//        if (Yii::$app->user->can('create-branch'))
//        if (Yii::$app->user->can('admin'))
//        {
            $model = new Branches();

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
                Yii::$app->response->format='json';
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post())) {
                $model->branch_created_date = date('Y-m-d h-i-s');
                if($model->save())  //Ако е сейвнат модела чрез AJAX пише 1 в console.log
                {
                    echo 1;
                }else
                {
                    echo 0;
                }
            } else {
                return $this->renderAjax('create', [    //Заменяме render с renderAjax за добра визуализация на формата
                    'model' => $model,
                ]);
            }
//        }else
//        {
//            throw new ForbiddenHttpException;
//        }

    }



    public function actionValidation()
    {
        $model = new Branches();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format='json';
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing Branches model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->branch_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Branches model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionLists($id)
    {
        $countBranches = Branches::find()
            ->where(['companies_company_id'=>$id])
            ->count();

        $branches = Branches::find()
            ->where(['companies_company_id'=>$id])
            ->all();

        if ($countBranches>0)
        {
            foreach ($branches as $branch)
            {
                echo "<option value='".$branch->branch_id."'>".$branch->branch_name."</option>";
            }
        }else{
            echo "<option> - </option>";
        }
    }




    /**
     * Finds the Branches model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branches the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branches::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
