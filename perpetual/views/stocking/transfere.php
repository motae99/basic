<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Inventory;
use app\models\Stock;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $trans app\models\Inventory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $products = Url::to(['inventory/product']);?>

<script>


// function products(){
//     var id  = $('#inventory-from').val();
//     $.ajax({
//          url: "<?=$products?>?id=" + id,
//          dataType: 'json',
//          method: 'GET',
//          // data: id,
//          success: function (data, textStatus, jqXHR) {
//             $('#invoiceproduct-' + index + '-selling_rate').val(data.selling_price) ;
//             $('#invoiceproduct-' + index + '-quantity').prop('min', 1);
//             $('#invoiceproduct-' + index + '-quantity').prop('max', data.quantity);
//             $('#invoiceproduct-' + index + '-quantity').prop('readonly', false);
//          },
//          //  beforeSend: function (xhr) {
//          //      alert('loading!');
//          // },
//           error: function (jqXHR, textStatus, errorThrown) {
//             $('#invoiceproduct-' + index + '-selling_rate').val('') ;
//             $('#invoiceproduct-' + index + '-quantity').val('') ;
//             $('#invoiceproduct-' + index + '-buying_rate').val('') ;
//             $('#invoiceproduct-' + index + '-quantity').prop('readonly', true);
//             // calculateTotal();
              
//           }
//     });

// }

</script>

<div class="transfere-form">

    <?php $form = ActiveForm::begin(
        [   
            'id' => 'inventory-transfere-form',
            'options'=>['method' => 'post'],
            'action' => Url::to(['stocking/transfere']),
            
        ]); 
    ?>

    <?= $form->field($trans, 'from')->dropDownList(ArrayHelper::map(Inventory::find()->all(), 'id', 'name'),
                [	
                	'id'=>'inventory-id',
                    'prompt'=>Yii::t('app', 'From Inventory'), 

                ])->label(false); 
    ?>
    
    <?= $form->field($trans, 'item')->widget(DepDrop::classname(), [
		        'options'=>['id'=>'stock-id'],
			    'pluginOptions'=>[
			        'depends'=>['inventory-id'],
		         	// 'placeholder' => Yii::t('app', 'Item'), 
		         	'url' => Url::to(['inventory/product'])
		     	]
		])->label(false);
    ?>

    <?= $form->field($trans, 'to')->dropDownList(ArrayHelper::map(Inventory::find()->all(), 'id', 'name'),
                [
                    'prompt'=>Yii::t('app', 'To Inventory'), 
                ])->label(false); 
    ?>

    <?= $form->field($trans, 'quantity')->textInput(['placeholder'=>Yii::t('inventory', 'Quantity'), 'type' => 'number', 'min' => 1])->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('inventory', 'Transfere'), ['class' => 'btn btn-flat btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
