<?php

class Drugs extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $drug_ndc_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $drug_commercial_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $drug_scientific_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $drug_company;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $drug_supplier_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $drug_in_inventory;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $drug_in_warehouse;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
        $this->belongsTo('drug_supplier_id', '\DrugSuppliers', 'supplier_id', ['alias' => 'DrugSuppliers']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Drugs';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Drugs[]|Drugs|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Drugs|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
