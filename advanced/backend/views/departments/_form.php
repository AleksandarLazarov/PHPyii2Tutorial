<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;  //Добавяменго за да имаме достъп до класа
use yii\widgets\ActiveForm;
use backend\models\Branches;    //Добавяменго за да имаме достъп до класа
use backend\models\Companies;   //Добавяменго за да имаме достъп до класа
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'companies_company_id')->textInput() ?-->
    <!-- Долното прави дропдаун меню, а горното е поле за попълване  в CREATE i UPDATE formata  -->
    <?= $form->field($model, 'companies_company_id')->dropDownList(
        ArrayHelper::map(Companies::find()->all(),'company_id','company_name'),
        [
            'prompt'=>'Select Company',
            'onchange'=>'       
                $.post("index.php?r=branches/lists&id='.'"+$(this).val(), function(data){
                    $("select#departments-branches_branch_id").html(data);  //departments-branches_branch_id -selctva samo branch na izbranata kompaniq
                });'
            //!!!Когато изберем компания в долното поле Select Branch можем да избираме само Branch-овете на тази компания
        ]
    ) ?>

    <!--?= $form->field($model, 'branches_branch_id')->textInput() ?-->
    <!-- Долното прави дропдаун меню, а горното е поле за попълване  в CREATE i UPDATE formata  -->
    <?= $form->field($model, 'branches_branch_id')->dropDownList(
            ArrayHelper::map(Branches::find()->all(),'branch_id','branch_name'),
            ['prompt'=>'Select Branch']
    ) ?>
    <!--?='<label class="control-label">Branch Name</label>';?-->
    <!--?= Select2::widget([                           //Това е multipleСЕЛЕКТ със SEARCH
    'name' => 'branches_branch_id',
    'value' => 'SelectBranch',
    'data' => ArrayHelper::map(Branches::find()->all(),'branch_id','branch_name'),
    'options' => ['multiple' => true, 'placeholder' => 'Select multiple states ...']
    ]);?-->



    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>


    <!--?= $form->field($model, 'department_created_date')->textInput() ?-->

    <?= $form->field($model, 'department_status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Status']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
