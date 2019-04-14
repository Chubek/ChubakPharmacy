<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class InsuranceInfoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Insurance_Info
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'InsuranceInfo', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "cust_insurance_id";

        $Insurance_Info = InsuranceInfo::find($parameters);
        if (count($Insurance_Info) == 0) {
            $this->flash->notice("The search did not find any Insurance_Info");

            $this->dispatcher->forward([
                "controller" => "Insurance_Info",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Insurance_Info,
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
     * Edits a Insurance_Info
     *
     * @param string $cust_insurance_id
     */
    public function editAction($cust_insurance_id)
    {
        if (!$this->request->isPost()) {

            $Insurance_Info = InsuranceInfo::findFirstBycust_insurance_id($cust_insurance_id);
            if (!$Insurance_Info) {
                $this->flash->error("Insurance_Info was not found");

                $this->dispatcher->forward([
                    'controller' => "Insurance_Info",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->cust_insurance_id = $Insurance_Info->cust_insurance_id;

            $this->tag->setDefault("cust_insurance_id", $Insurance_Info->cust_insurance_id);
            $this->tag->setDefault("cust_insurance_name", $Insurance_Info->cust_insurance_name);
            $this->tag->setDefault("cust_courier_id", $Insurance_Info->cust_courier_id);
            $this->tag->setDefault("cust_insruance_expires", $Insurance_Info->cust_insruance_expires);
            
        }
    }

    /**
     * Creates a new Insurance_Info
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'index'
            ]);

            return;
        }

        $Insurance_Info = new InsuranceInfo();
        $Insurance_Info->cust_insurance_name = $this->request->getPost("cust_insurance_name");
        $Insurance_Info->cust_courier_id = $this->request->getPost("cust_courier_id");
        $Insurance_Info->cust_insruance_expires = $this->request->getPost("cust_insruance_expires");
        

        if (!$Insurance_Info->save()) {
            foreach ($Insurance_Info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Insurance_Info was created successfully");

        $this->dispatcher->forward([
            'controller' => "Insurance_Info",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Insurance_Info edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'index'
            ]);

            return;
        }

        $cust_insurance_id = $this->request->getPost("cust_insurance_id");
        $Insurance_Info = InsuranceInfo::findFirstBycust_insurance_id($cust_insurance_id);

        if (!$Insurance_Info) {
            $this->flash->error("Insurance_Info does not exist " . $cust_insurance_id);

            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'index'
            ]);

            return;
        }

        $Insurance_Info->cust_insurance_name = $this->request->getPost("cust_insurance_name");
        $Insurance_Info->cust_courier_id = $this->request->getPost("cust_courier_id");
        $Insurance_Info->cust_insruance_expires = $this->request->getPost("cust_insruance_expires");
        

        if (!$Insurance_Info->save()) {

            foreach ($Insurance_Info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'edit',
                'params' => [$Insurance_Info->cust_insurance_id]
            ]);

            return;
        }

        $this->flash->success("Insurance_Info was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Insurance_Info",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Insurance_Info
     *
     * @param string $cust_insurance_id
     */
    public function deleteAction($cust_insurance_id)
    {
        $Insurance_Info = InsuranceInfo::findFirstBycust_insurance_id($cust_insurance_id);
        if (!$Insurance_Info) {
            $this->flash->error("Insurance_Info was not found");

            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Insurance_Info->delete()) {

            foreach ($Insurance_Info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Insurance_Info",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Insurance_Info was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Insurance_Info",
            'action' => "index"
        ]);
    }

}
