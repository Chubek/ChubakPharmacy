<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DoctorsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Doctors
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Doctors', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "doc_medical_lic_id";

        $Doctors = Doctors::find($parameters);
        if (count($Doctors) == 0) {
            $this->flash->notice("The search did not find any Doctors");

            $this->dispatcher->forward([
                "controller" => "Doctors",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Doctors,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a Doctor
     *
     * @param string $doc_medical_lic_id
     */
    public function editAction($doc_medical_lic_id)
    {
        if (!$this->request->isPost()) {

            $Doctor = Doctors::findFirstBydoc_medical_lic_id($doc_medical_lic_id);
            if (!$Doctor) {
                $this->flash->error("Doctor was not found");

                $this->dispatcher->forward([
                    'controller' => "Doctors",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->doc_medical_lic_id = $Doctor->doc_medical_lic_id;

            $this->tag->setDefault("doc_medical_lic_id", $Doctor->doc_medical_lic_id);
            $this->tag->setDefault("doc_first_name", $Doctor->doc_first_name);
            $this->tag->setDefault("doc_middle_name", $Doctor->doc_middle_name);
            $this->tag->setDefault("doc_last_name", $Doctor->doc_last_name);
            $this->tag->setDefault("doc_date_of_birth", $Doctor->doc_date_of_birth);
            $this->tag->setDefault("doc_hire_date", $Doctor->doc_hire_date);
            $this->tag->setDefault("doc_landline", $Doctor->doc_landline);
            $this->tag->setDefault("doc_mobile", $Doctor->doc_mobile);
            $this->tag->setDefault("doc_email", $Doctor->doc_email);
            $this->tag->setDefault("doc_id_photo", $Doctor->doc_id_photo);
            
        }
    }

    /**
     * Creates a new Doctor
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'index'
            ]);

            return;
        }

        $Doctor = new Doctors();
        $Doctor->doc_medical_lic_id = $this->request->getPost("doc_medical_lic_id");
        $Doctor->doc_first_name = $this->request->getPost("doc_first_name");
        $Doctor->doc_middle_name = $this->request->getPost("doc_middle_name");
        $Doctor->doc_last_name = $this->request->getPost("doc_last_name");
        $Doctor->doc_date_of_birth = $this->request->getPost("doc_date_of_birth");
        $Doctor->doc_hire_date = $this->request->getPost("doc_hire_date");
        $Doctor->doc_landline = $this->request->getPost("doc_landline");
        $Doctor->doc_mobile = $this->request->getPost("doc_mobile");
        $Doctor->doc_email = $this->request->getPost("doc_email");
        $Doctor->doc_id_photo = $this->request->getPost("doc_id_photo");
        

        if (!$Doctor->save()) {
            foreach ($Doctor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Doctor was created successfully");

        $this->dispatcher->forward([
            'controller' => "Doctors",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Doctor edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'index'
            ]);

            return;
        }

        $doc_medical_lic_id = $this->request->getPost("doc_medical_lic_id");
        $Doctor = Doctors::findFirstBydoc_medical_lic_id($doc_medical_lic_id);

        if (!$Doctor) {
            $this->flash->error("Doctor does not exist " . $doc_medical_lic_id);

            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'index'
            ]);

            return;
        }

        $Doctor->doc_medical_lic_id = $this->request->getPost("doc_medical_lic_id");
        $Doctor->doc_first_name = $this->request->getPost("doc_first_name");
        $Doctor->doc_middle_name = $this->request->getPost("doc_middle_name");
        $Doctor->doc_last_name = $this->request->getPost("doc_last_name");
        $Doctor->doc_date_of_birth = $this->request->getPost("doc_date_of_birth");
        $Doctor->doc_hire_date = $this->request->getPost("doc_hire_date");
        $Doctor->doc_landline = $this->request->getPost("doc_landline");
        $Doctor->doc_mobile = $this->request->getPost("doc_mobile");
        $Doctor->doc_email = $this->request->getPost("doc_email");
        $Doctor->doc_id_photo = $this->request->getPost("doc_id_photo");
        

        if (!$Doctor->save()) {

            foreach ($Doctor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'edit',
                'params' => [$Doctor->doc_medical_lic_id]
            ]);

            return;
        }

        $this->flash->success("Doctor was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Doctors",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Doctor
     *
     * @param string $doc_medical_lic_id
     */
    public function deleteAction($doc_medical_lic_id)
    {
        $Doctor = Doctors::findFirstBydoc_medical_lic_id($doc_medical_lic_id);
        if (!$Doctor) {
            $this->flash->error("Doctor was not found");

            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Doctor->delete()) {

            foreach ($Doctor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctors",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Doctor was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Doctors",
            'action' => "index"
        ]);
    }

}
