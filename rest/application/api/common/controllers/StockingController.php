<?php
namespace api\common\controllers;
use \Yii as Yii;
// use api\models\User;
use api\common\models\Stocking;


class StockingController extends \api\components\ActiveController
{
    public $modelClass = '\api\common\models\Stocking';

    public function accessRules()
    {
        return [
            [
                'allow' => true,
                'roles' => ['?'],
            ],
           
        ];
    }

    public function actions(){
        $actions = parent::actions();
        // unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

}