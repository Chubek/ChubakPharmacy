<?php

class Doctors extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $doc_medical_lic_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $doc_first_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $doc_middle_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $doc_last_name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $doc_date_of_birth;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $doc_hire_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $doc_landline;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $doc_mobile;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $doc_email;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $doc_id_photo;

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
        return 'Doctors';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Doctors[]|Doctors|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Doctors|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
