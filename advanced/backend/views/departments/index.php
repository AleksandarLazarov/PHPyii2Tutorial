<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Departments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [   //Tova показва company_name вместо companies_company_id в грида със записите
                'attribute'=>'companies_company_id',
                'value'=>'companiesCompany.company_name',
            ],
            [   //Tova показва branch_name вместо branches_branch_id в грида със записите
                'attribute'=>'branches_branch_id',
                'value'=>'branchesBranch.branch_name',
            ],
//            'department_id',
            //'companiesCompany.company_name',      //Това показва името на компанията, но без Search по стойност
//            'branchesBranch.branch_name',         //Това показва името на компанията, но без Search по стойност
            'department_name',
            'department_created_date',
            // 'department_status',

        ['class' => 'yii\grid\ActionColumn',],       //това е последната колона от грида
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
