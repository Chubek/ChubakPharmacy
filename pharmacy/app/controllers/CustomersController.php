<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CustomersController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Customers
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Customers', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "cust_id";

        $Customers = Customers::find($parameters);
        if (count($Customers) == 0) {
            $this->flash->notice("The search did not find any Customers");

            $this->dispatcher->forward([
                "controller" => "Customers",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Customers,
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
     * Edits a Customer
     *
     * @param string $cust_id
     */
    public function editAction($cust_id)
    {
        if (!$this->request->isPost()) {

            $Customer = Customers::findFirstBycust_id($cust_id);
            if (!$Customer) {
                $this->flash->error("Customer was not found");

                $this->dispatcher->forward([
                    'controller' => "Customers",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->cust_id = $Customer->cust_id;

            $this->tag->setDefault("cust_id", $Customer->cust_id);
            $this->tag->setDefault("cust_first_name", $Customer->cust_first_name);
            $this->tag->setDefault("cust_middle_name", $Customer->cust_middle_name);
            $this->tag->setDefault("cust_last_name", $Customer->cust_last_name);
            $this->tag->setDefault("cust_date_of_birth", $Customer->cust_date_of_birth);
            $this->tag->setDefault("cust_gender", $Customer->cust_gender);
            $this->tag->setDefault("cust_address", $Customer->cust_address);
            $this->tag->setDefault("cust_landline", $Customer->cust_landline);
            $this->tag->setDefault("cust_mobile", $Customer->cust_mobile);
            $this->tag->setDefault("cust_insurance_id", $Customer->cust_insurance_id);
            
        }
    }

    /**
     * Creates a new Customer
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'index'
            ]);

            return;
        }

        $Customer = new Customers();
        $Customer->cust_first_name = $this->request->getPost("cust_first_name");
        $Customer->cust_middle_name = $this->request->getPost("cust_middle_name");
        $Customer->cust_last_name = $this->request->getPost("cust_last_name");
        $Customer->cust_date_of_birth = $this->request->getPost("cust_date_of_birth");
        $Customer->cust_gender = $this->request->getPost("cust_gender");
        $Customer->cust_address = $this->request->getPost("cust_address");
        $Customer->cust_landline = $this->request->getPost("cust_landline");
        $Customer->cust_mobile = $this->request->getPost("cust_mobile");
        $Customer->cust_insurance_id = $this->request->getPost("cust_insurance_id");
        

        if (!$Customer->save()) {
            foreach ($Customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Customer was created successfully");

        $this->dispatcher->forward([
            'controller' => "Customers",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Customer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'index'
            ]);

            return;
        }

        $cust_id = $this->request->getPost("cust_id");
        $Customer = Customers::findFirstBycust_id($cust_id);

        if (!$Customer) {
            $this->flash->error("Customer does not exist " . $cust_id);

            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'index'
            ]);

            return;
        }

        $Customer->cust_first_name = $this->request->getPost("cust_first_name");
        $Customer->cust_middle_name = $this->request->getPost("cust_middle_name");
        $Customer->cust_last_name = $this->request->getPost("cust_last_name");
        $Customer->cust_date_of_birth = $this->request->getPost("cust_date_of_birth");
        $Customer->cust_gender = $this->request->getPost("cust_gender");
        $Customer->cust_address = $this->request->getPost("cust_address");
        $Customer->cust_landline = $this->request->getPost("cust_landline");
        $Customer->cust_mobile = $this->request->getPost("cust_mobile");
        $Customer->cust_insurance_id = $this->request->getPost("cust_insurance_id");
        

        if (!$Customer->save()) {

            foreach ($Customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'edit',
                'params' => [$Customer->cust_id]
            ]);

            return;
        }

        $this->flash->success("Customer was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Customers",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Customer
     *
     * @param string $cust_id
     */
    public function deleteAction($cust_id)
    {
        $Customer = Customers::findFirstBycust_id($cust_id);
        if (!$Customer) {
            $this->flash->error("Customer was not found");

            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Customer->delete()) {

            foreach ($Customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Customers",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Customer was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Customers",
            'action' => "index"
        ]);
    }

}
