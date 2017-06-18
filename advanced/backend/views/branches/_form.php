<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Companies;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Branches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branches-form">


    <?php $form = ActiveForm::begin([
        'id'=>$model->formName(),
        'enableAjaxValidation'=>true,
        'validationUrl'=>Url::toRoute('branches/validation'),
    ]);?>


    <!--?= $form->field($model, 'companies_company_id')->textInput() ?-->
<!--?= $form->field($model, 'companies_company_id')->dropDownList(         //tova pravi dropDownList v CREATE I UPDATE
           ArrayHelper::map(Companies::find()->all(),'company_id','company_name'),     //company_name e poleto ot koeto Selektvame
            ['prompt'=>'SelectCompany']     //SelectCompany e DEFAULT STOINOST
        ) ?-->
    <?= $form->field($model, 'companies_company_id')->widget(Select2::classname(), [    //Добавя търсене в dropDownList-а
        'data' => ArrayHelper::map(Companies::find()->all(),'company_id','company_name'),     //company_name e poleto s multiSELECT
        'language' => 'en',
        'options' => ['placeholder' => 'Select a state ...'],
        'pluginOptions' => [
        'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_address')->textInput(['maxlength' => true]) ?>

    <!--?= $form->field($model, 'branch_created_date')->textInput() ?-->

    <?= $form->field($model, 'branch_status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Status']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
//Това е JS-а за AJAX субмит
$script = <<< JS
    
$('form#{$model->formName()}').on('beforeSubmit', function(e)
{
    var \$form = $(this);
   $.post(
        \$form.attr("action"), //serialize Yii2 form
        \$form.serialize()
   )
        .done(function(result) {
        if(result == 1)
        {
            // $(document).find('#secondmodal').modal('hide');
            $(\$form).trigger("reset");
            $.pjax.reload({container:'#branchesGrid'});            
        }else
        {
            $("#message").html(result);
        }
        }).fail(function() 
        {
            console.log("server error");  
        }); 
    return false;
});

JS;
$this->registerJs($script);
?>