<?php

class Customers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $cust_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $cust_first_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $cust_middle_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $cust_last_name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $cust_date_of_birth;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $cust_gender;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $cust_address;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $cust_landline;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $cust_mobile;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $cust_insurance_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
        $this->belongsTo('cust_insurance_id', '\InsuranceInfo', 'cust_insurance_id', ['alias' => 'InsuranceInfo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Customers';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customers[]|Customers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
