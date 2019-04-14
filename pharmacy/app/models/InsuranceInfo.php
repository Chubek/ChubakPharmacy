<?php

class InsuranceInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $cust_insurance_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $cust_insurance_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $cust_courier_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $cust_insruance_expires;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
        $this->belongsTo('cust_courier_id', '\InsuranceCouriers', 'courier_id', ['alias' => 'InsuranceCouriers']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Insurance_Info';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return InsuranceInfo[]|InsuranceInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return InsuranceInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
