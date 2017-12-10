<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('invo', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">
    <br>
    
    
    <?php 
        $gridColumns  = 
        [   
            [ 
                'class'=>'kartik\grid\ExpandRowColumn',
                'width'=>'5%',
                'value'=>function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_inner', ['model'=>$model]);
                },
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'expandOneOnly'=>true
            ],
            /*[
                // 'attribute'=>'inventory_id',
                'header'=> Yii::t('invo', 'checkMe'),
                // 'width'=>'26%',
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                // 'value' =>function ($model, $key, $index, $widget) { 
                //     return $model->inventory->name;                    
                //     },

                
            ],*/
            [
                'class'=>'kartik\grid\DataColumn',
                // 'attribute'=>'client_id',
                'header'=> Yii::t('invo', 'Client'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'group'=>true,
                // 'width'=>'26%',
                'format' => 'raw',
                'value' =>function ($model, $key, $index, $widget) { 
                    return $model->client->client_name; 
                                      
                    },

                // 'contentOptions' => function ($model, $key, $index, $column) {
                //     $active = $model->product->active;
                //     if ($active == "percentage") {
                //         return ['style' => 'color:green; font-weight: bold;' ];
                //     }
                    
                // },
            ],
            [
                'class'=>'kartik\grid\DataColumn',
                'attribute'=>'id',
                'header'=> Yii::t('invo', '#NO'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'group'=>true,
                'width'=>'8%',
                'format' => 'raw',
                // 'value' =>function ($model, $key, $index, $widget) { 
                //     return $model->client->client_name; 
                                      
                //     },

                // 'contentOptions' => function ($model, $key, $index, $column) {
                //     $active = $model->product->active;
                //     if ($active == "percentage") {
                //         return ['style' => 'color:green; font-weight: bold;' ];
                //     }
                    
                // },
            ],
            [
                'class'=>'kartik\grid\DataColumn',
                'attribute'=>'cost',
                'header'=> Yii::t('invo', 'Cost'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'footer'=>true ,
                'pageSummary'=>true,
                'width'=>'10%',
                'format' => 'raw',
                // 'value' =>function ($model, $key, $index, $widget) { 
                //     return $model->trans($model);                    
                // },
                // 'contentOptions' => function ($model, $key, $index, $column) {
                //     $active = $model->product->active;
                //     if ($active == "percentage") {
                //         return ['style' => 'color:green; font-weight: bold;' ];
                //     }
                    
                // },
            ],
            [
                'class'=>'kartik\grid\DataColumn',
                'attribute'=>'amount',
                'header'=> Yii::t('invo', 'Total'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'width'=>'10%',
                'format' => 'raw',
                'pageSummary'=> true,
               /* 'value' =>function ($model, $key, $index, $widget) { 
                    return $model->in($model);                    
                },
                'contentOptions' => function ($model, $key, $index, $column) {
                    $active = $model->product->active;
                    if ($active == "percentage") {
                        return ['style' => 'color:green; font-weight: bold;' ];
                    }
                    
                },*/
            ],
            [  
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>Yii::t('invo', 'Gross Sale'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                // 'format'=>['decimal', 2],
                'mergeHeader'=>true, 
                'width'=>'10%',
                'hAlign'=>'center', 
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(4, $p) - $widget->col(3, $p) ;
                },
                
                'pageSummary'=>true,
                'footer'=>true 
            ],
            [
                'class'=>'kartik\grid\DataColumn',
                'attribute'=>'status',
                'header'=> Yii::t('invo', 'Status'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'width'=>'5%',
                'format' => 'raw',
                'value' =>function ($model, $key, $index, $widget) { 
                    if ($model->status == "paid") {
                        return '<i class="fa fa-2x fa-check-circle" style="color: green;"></i>';
                    }elseif($model->status == "partially"){
                        return '<i class="fa fa-2x fa-minus-circle" style="color: orange;"></i>';
                    }else{
                        return '<i class="fa fa-2x fa-times-circle" style="color: red;"></i>';
                    }
                                      
                },
            ],
            [
                'attribute'=>'date',
                'header'=> Yii::t('invo', 'Date'),
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'hAlign'=>'center',
                'format' => 'raw',
                'width'=>'8%',
                /*'value' =>function ($model, $key, $index, $widget) { 
                    $current_rate = Yii::$app->mycomponent->rate();
                    if ($current_rate > $model->highest_rate) {
                        $rate = $current_rate;
                    }else{
                       $rate = $model->highest_rate; 
                    }
                    return $rate;                    
                },*/
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => Yii::t('invo', 'View'),
                'width' => '5%',
                'template' => '{view} ',
                // 'viewOptions'=>['lable'=>'<i class="glyphicon glyphicon-remove"></i>'],
                // 'updateOptions'=>['null' => true],
                // 'deleteOptions'=>['null' => true],
            ],
        ]
    ?>

    <?php echo  GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => $gridColumns,

        /*'rowOptions' => function ($model) {
            if ($model->status !== "paid") {
                return ['class' => 'danger'];
            }
        },*/
        'pjax' => true,
        'pjaxSettings'=>[
          'neverTimeout'=>true,
            'options'=>
              [
                'id'=>'Invoices',
              ],
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'responsiveWrap' => true,
        'hover' => true,
        // 'floatHeader' => true,
       // 'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
        'showPageSummary' => true,
        // 'panel' => [
        //     'type' => GridView::TYPE_INFO,
        //     'heading' => '<i class="fa  fa-hospital-o"></i><strong></strong>',

        // ],
        
    ]); ?>
</div>
