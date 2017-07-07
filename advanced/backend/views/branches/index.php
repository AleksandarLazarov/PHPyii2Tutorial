<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;     //Search s AJAX
use yii\bootstrap\Modal;    //Добавяме това за да молзваме класа Modal
use yii\helpers\Url;      //Добавяме това за да молзваме класа Url


/* @var $this yii\web\View */
/* @var $searchModel backend\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create Branches', ['create'], ['class' => 'btn btn-success']) ?-->
        <?= Html::button('Create Branches', ['value'=>Url::to('index.php?r=branches/create'), 'class' => 'btn btn-success','id'=>'modalButton']) ?>
    </p>

    <?php
    Modal::begin([          //това също трябва да се добави за да имаме Pop up Form
            'header'=>'<h4>Branches</h4>',
            'id'=>'modal',
            'size'=>'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php Pjax::begin(['id'=>'branchesGrid']);  //Search s AJAX е без ID-то //ID-то е за AJAX Submit-а ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if ($model->branch_status == 'inactive'){
                return ['class'=>'danger'];
            }else{
                return ['class'=>'success'];
            }

        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [                                         //Tova pravi Search na GridView-to aktiven
                'attribute'=>'companies_company_id',
                'value'=>'companiesCompany.company_name',
            ],
//            'branch_id',      //Skrivane ot GRID-a
//            'companiesCompany.company_name',
            'branch_name',
            'branch_address',
            'branch_created_date',
             'branch_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end();    //Search s AJAX ?>
</div>
