<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2; //включва Dependent DropDownLists
use \backend\models\Locations;



/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'custom_name')->textInput(['maxlength' => true]) ?>

    <!--?= $form->field($model, 'zip_code')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'zip_code')->widget(Select2::classname(), [    //Добавя търсене в dropDownList-а
        'data' => ArrayHelper::map(Locations::find()->all(),'location_id','zip_code'),     //zip_code e poleto от Location table
        'language' => 'en',
        'options' => ['placeholder' => 'Select a Zip Code ...','id'=>'zipCode'],    //Задаване на ID
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
//JS function
$script = <<< JS
//here you right all your javascript stuff
$('#zipCode').change(function() {       //При промяна на поле с ID=zipCode 
    var zipId = $(this).val();      //Взима текущата избрана стойност
    
    $.get('index.php?r=locations/get-city-province',{zipId : zipId},function(data) {    //Взима пътя до екшъна на контролера 
                                                    //задава стойност на zipId : zipId 
        var data = $.parseJSON(data);        //парсва информацията към JSON
        $('#customers-city').attr('value',data.city);   //Слага стойността от Location table
        $('#customers-province').attr('value',data.province);   //Слага стойността от Location table
    });
});


JS;
$this->registerJs($script);
?>