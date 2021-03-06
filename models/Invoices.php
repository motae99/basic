<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%invoices}}".
 *
 * @property int $id
 * @property int $client_id
 * @property string $amount
 * @property string $method
 * @property string $date
 * @property string $status
 * @property string $created_at
 *
 * @property InvoiceProduct[] $invoiceProducts
 * @property Client $client
 * @property Payments[] $payments
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $pay ;
    public $amountDue ;
    public static function tableName()
    {
        return '{{%invoices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'amount'], 'required'],
            [['client_id'], 'integer'],
            [['amount', 'pay'], 'number'],
            [['method', 'status'], 'string'],
            [['date', 'created_at'], 'safe'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'amount' => Yii::t('app', 'Amount'),
            'method' => Yii::t('app', 'Method'),
            'date' => Yii::t('app', 'Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'pay' => Yii::t('app', 'Pay'),
            'inventory_id' => Yii::t('app', 'inventory_id'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::className(), ['invoice_id' => 'id'])->onCondition(['returned' => '0']);
    }

    public function getInvoiceProductsAll()
    {
        return $this->hasMany(InvoiceProduct::className(), ['invoice_id' => 'id']);
    }

    public function getInvoiceReturnedProducts()
    {
        return $this->hasMany(InvoiceProduct::className(), ['invoice_id' => 'id'])->onCondition(['returned' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getReturnedPayment()
    {
        return $this->hasOne(ReturnedPayment::className(), ['invoice_id' => 'id']);
    }

    public function getInventory()
    {
        return $this->hasOne(Inventory::className(), ['id' => 'inventory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['invoice_id' => 'id']);
    }

    public function getTotalPaid()
    {
        return $this->hasMany(Payments::className(), ['invoice_id' => 'id'])->sum('amount');
    }

    public function getOutstanding()
    {
        return $this->hasMany(Outstanding::className(), ['invoice_id' => 'id'])->onCondition(['status' => 'outstanding']);
    }

    /**
     * @inheritdoc
     * @return InvoicesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoicesQuery(get_called_class());
    }

    function ex_exists($ex, $array) {
        $result = -1;
        for($i=0; $i<sizeof($array); $i++) {
            if ($array[$i]['inventory_id'] == $ex) {
                $result = $i;
                break;
            }
        }
        return $result;
    }
}
