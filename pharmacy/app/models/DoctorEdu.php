<?php

class DoctorEdu extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $doc_edu_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $doc_medical_lic_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $doc_university;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $doc_major;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $doc_degree;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("pharmacy");
        $this->belongsTo('doc_medical_lic_id', '\Doctors', 'doc_medical_lic_id', ['alias' => 'Doctors']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Doctor_Edu';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DoctorEdu[]|DoctorEdu|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DoctorEdu|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
