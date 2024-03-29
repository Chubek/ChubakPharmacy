<?php

class DrugSuppliers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $supplier_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $supplier_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $supplier_address;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $supplier_landline;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $supplier_mobile;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Drug_Suppliers';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DrugSuppliers[]|DrugSuppliers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DrugSuppliers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
