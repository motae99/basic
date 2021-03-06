<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Stock;
use app\models\Client;
use kartik\select2\Select2;
use yii\web\JsExpression;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = Yii::t('invo', 'Create Invoices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('invo', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    // $product_details = \yii\helpers\Url::to(['details']);
    
?>

<script>


function check(item) {
    var index  = item.attr("id").replace(/[^0-9.]/g, "");  
    
    var quantity = $('#invoiceproduct-' + index + '-quantity').val();
    quantity = quantity == "" ? 0 : Number(quantity.split(",").join(""));
    
    var selling_rate = $('#invoiceproduct-' + index + '-selling_rate').val();
    selling_rate = selling_rate == "" ? 0 : Number(selling_rate.split(",").join(""));
    var discount = $('#invoiceproduct-' + index + '-discount').val();

    $('#invoiceproduct-' + index + '-buying_rate').val(selling_rate * quantity -discount);  

        calculateTotal();
}

// function 

//total price calculation 
function calculateTotal(){
    subTotal = 0 ;
    $('.totalLinePrice').each(function(){
        if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
    });
    $('#invoices-amount').val( subTotal );
    $('#invoices-pay').prop('max', subTotal);
    $('#invoices-pay').val( subTotal );
    
    // calculateAmountDue();

    // $('#invoices-amount').val( subTotal.toFixed(2) );
    // tax = $('#tax').val();
    // if(tax != '' && typeof(tax) != "undefined" ){
    //     taxAmount = subTotal * ( parseFloat(tax) /100 );
    //     $('#taxAmount').val(taxAmount.toFixed(2));
    //     total = subTotal + taxAmount;
    // }else{
    //     $('#taxAmount').val(0);
    //     total = subTotal;
    // }
    // $('#totalAftertax').val( total.toFixed(2) );
}



//due amount calculation
function calculateAmountDue(){
    // amountPaid = $('#invoices-pay').val();
    // total = $('#invoices-amount').val();
    // if(amountPaid <= total ){
    //     amountDue = parseFloat(total) - parseFloat( amountPaid );
    //     // alert("amount due is :" + amountDue);
    //     // $('.amountDue').val( amountDue.toFixed(2) );
    //     $('#info').show();
    //     $('.showAmount').text(data.amountDue);

    // }else{
    //     total = parseFloat(total).toFixed(2);
    //     // $('.amountDue').val( total);
    //     $('#invoices-pay').val('0');
    //     $('#info').show();
    //     $('.showAmount').text(data.amountDue);
    // }
}

</script>

<div class="invoices-form" style="margin-top: 30px;">
<div class="row">
<div class="col-sm-12">
<div class="box">
    <?php $form = ActiveForm::begin([ 
            'id'=>"dynamic-form",
            'options'=>['method' => 'post'],
            'action' => Url::to(['invoices/update', 'id' => $model->id])
            ]);  
    ?>
       

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 10, // the maximum times, an element can be added (default 999)
        // 'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelsItem[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'product_id',
            'quantity',
        ],
    ]); ?>

    <table class="table table-borderd table-responsive container-items">
        <tr class="bg-maroon">
            <th style="width: 40%;"><?=Yii::t('invo', 'Item')?></th>
            <th style="width: 13%;"><?=Yii::t('invo', 'Quantity')?></th>
            <th style="width: 15%;"><?=Yii::t('invo', 'Price')?></th>
            <th style="width: 13%;"><?=Yii::t('invo', 'Discount')?></th>
            <th style="width: 15%;"><?=Yii::t('invo', 'LineTotal')?></th>
            <th style="width: 4%;">
                <button type="button" class=" btn btn-xs"><i class="bg-maroon"></i></button>
            </th>
        </tr>
        <?php foreach ($modelsItem as $i => $modelItem): ?>
        <tr class="item">
            <?php
                // necessary for update action.
                if (! $modelItem->isNewRecord) {
                    echo Html::activeHiddenInput($modelItem, "[{$i}]id");
                }
            ?>
            <td style="width: 40%;">
                <?= $form->field($modelItem, "[{$i}]product_id")->textInput(

                        [
                            'disabled' => true,
                            'onchange'=> 'pro($(this))',
                            'value'=> $modelItem->product->product_name
                        ])->label(false); 
                ?>
            </td>
            <td style="width: 13%;">
                <?= $form->field($modelItem, "[{$i}]quantity")
                    ->textInput(
                        [
                            'type' => 'number', 
                            'onchange' => 'check($(this))', 
                            'placeholder'=>'Quantity',
                            'min'=> 1,
                            'max'=>$modelItem->quantity,
                        ])
                    ->label(false) 
                ?>
            </td>
            <td style="width: 15%;">
                <?= $form->field($modelItem, "[{$i}]selling_rate")
                    ->textInput(
                        [
                            // 'type' => 'number',
                            'placeholder'=>'Price',
                            'disabled' => true,
                            // 'value'=> $modelItem->selling_rate*$modelItem->d_rate
                            // 'onchange' => 'check($(this))',
                        ])
                    ->label(false) 
                ?>
            </td>
            <td style="width: 13%;">
                <?= $form->field($modelItem, "[{$i}]discount")
                    ->textInput(
                        [
                            // 'type' => 'number',
                            'placeholder'=>Yii::t('invo', 'Discount'),
                            'readonly' => true,
                            'min' => 1,
                            'onchange' => 'check($(this))',
                        ])
                    ->label(false) 
                ?>
            </td>
            <td style="width: 15%;">
                <?= $form->field($modelItem, "[{$i}]buying_rate")
                    ->textInput(
                        [   
                            'class' => 'form-control totalLinePrice',
                            // 'type' => 'number', 
                            'disabled' => True,
                            'onchange' => 'calculateTotal()',
                            'placeholder'=>'LineTotal',
                            'value'=> $modelItem->selling_rate*$modelItem->quantity-$modelItem->discount
                        ])
                    ->label(false)
                ?>
            </td>
            <td style="width: 4%;">
                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
            </td>
        <?php endforeach; ?>
    </table>
    <hr style=" border-bottom: 1px solid #000;">

    <?php DynamicFormWidget::end(); ?>


    <div class="col-sm-5 eArLangCss">
        <?= $form->field($model, 'amount')
            ->textInput(
                [
                    'placeholder'=>Yii::t('invo', 'Total'), 
                    'onchange'=> 'calculateAmountDue()',
                    // 'type' => 'number',
                    'readonly' => true,
                ])->label(false) 
        ?>
    </div>
        
        
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('invo', 'Return'), ['class' => "bg-maroon btn btn-flat btn-block"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div>


<?php 
$script = <<< JS
$(".dynamicform_wrapper").on("afterDelete", function(e) {
    calculateTotal();
});

$(document).on('change keyup blur','.invoices-pay',function(){
    calculateAmountDue();
});

JS;
$this->registerJs($script);
?>  
