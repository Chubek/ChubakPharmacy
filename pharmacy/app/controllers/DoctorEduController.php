<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DoctorEduController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Doctor_Edu
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'DoctorEdu', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "doc_edu_id";

        $Doctor_Edu = DoctorEdu::find($parameters);
        if (count($Doctor_Edu) == 0) {
            $this->flash->notice("The search did not find any Doctor_Edu");

            $this->dispatcher->forward([
                "controller" => "Doctor_Edu",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Doctor_Edu,
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
     * Edits a Doctor_Edu
     *
     * @param string $doc_edu_id
     */
    public function editAction($doc_edu_id)
    {
        if (!$this->request->isPost()) {

            $Doctor_Edu = DoctorEdu::findFirstBydoc_edu_id($doc_edu_id);
            if (!$Doctor_Edu) {
                $this->flash->error("Doctor_Edu was not found");

                $this->dispatcher->forward([
                    'controller' => "Doctor_Edu",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->doc_edu_id = $Doctor_Edu->doc_edu_id;

            $this->tag->setDefault("doc_edu_id", $Doctor_Edu->doc_edu_id);
            $this->tag->setDefault("doc_medical_lic_id", $Doctor_Edu->doc_medical_lic_id);
            $this->tag->setDefault("doc_university", $Doctor_Edu->doc_university);
            $this->tag->setDefault("doc_major", $Doctor_Edu->doc_major);
            $this->tag->setDefault("doc_degree", $Doctor_Edu->doc_degree);
            
        }
    }

    /**
     * Creates a new Doctor_Edu
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'index'
            ]);

            return;
        }

        $Doctor_Edu = new DoctorEdu();
        $Doctor_Edu->doc_medical_lic_id = $this->request->getPost("doc_medical_lic_id");
        $Doctor_Edu->doc_university = $this->request->getPost("doc_university");
        $Doctor_Edu->doc_major = $this->request->getPost("doc_major");
        $Doctor_Edu->doc_degree = $this->request->getPost("doc_degree");
        

        if (!$Doctor_Edu->save()) {
            foreach ($Doctor_Edu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Doctor_Edu was created successfully");

        $this->dispatcher->forward([
            'controller' => "Doctor_Edu",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Doctor_Edu edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'index'
            ]);

            return;
        }

        $doc_edu_id = $this->request->getPost("doc_edu_id");
        $Doctor_Edu = DoctorEdu::findFirstBydoc_edu_id($doc_edu_id);

        if (!$Doctor_Edu) {
            $this->flash->error("Doctor_Edu does not exist " . $doc_edu_id);

            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'index'
            ]);

            return;
        }

        $Doctor_Edu->doc_medical_lic_id = $this->request->getPost("doc_medical_lic_id");
        $Doctor_Edu->doc_university = $this->request->getPost("doc_university");
        $Doctor_Edu->doc_major = $this->request->getPost("doc_major");
        $Doctor_Edu->doc_degree = $this->request->getPost("doc_degree");
        

        if (!$Doctor_Edu->save()) {

            foreach ($Doctor_Edu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'edit',
                'params' => [$Doctor_Edu->doc_edu_id]
            ]);

            return;
        }

        $this->flash->success("Doctor_Edu was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Doctor_Edu",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Doctor_Edu
     *
     * @param string $doc_edu_id
     */
    public function deleteAction($doc_edu_id)
    {
        $Doctor_Edu = DoctorEdu::findFirstBydoc_edu_id($doc_edu_id);
        if (!$Doctor_Edu) {
            $this->flash->error("Doctor_Edu was not found");

            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Doctor_Edu->delete()) {

            foreach ($Doctor_Edu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Doctor_Edu",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Doctor_Edu was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Doctor_Edu",
            'action' => "index"
        ]);
    }

}
