<?php

class DrugCouriers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $drug_courier_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $drug_ndc_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $courier_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
        $this->belongsTo('drug_ndc_id', '\Drugs', 'drug_ndc_id', ['alias' => 'Drugs']);
        $this->belongsTo('courier_id', '\InsuranceCouriers', 'courier_id', ['alias' => 'InsuranceCouriers']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Drug_Couriers';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DrugCouriers[]|DrugCouriers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DrugCouriers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
