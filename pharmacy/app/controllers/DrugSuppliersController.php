<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DrugSuppliersController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Drug_Suppliers
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'DrugSuppliers', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "supplier_id";

        $Drug_Suppliers = DrugSuppliers::find($parameters);
        if (count($Drug_Suppliers) == 0) {
            $this->flash->notice("The search did not find any Drug_Suppliers");

            $this->dispatcher->forward([
                "controller" => "Drug_Suppliers",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Drug_Suppliers,
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
     * Edits a Drug_Supplier
     *
     * @param string $supplier_id
     */
    public function editAction($supplier_id)
    {
        if (!$this->request->isPost()) {

            $Drug_Supplier = DrugSuppliers::findFirstBysupplier_id($supplier_id);
            if (!$Drug_Supplier) {
                $this->flash->error("Drug_Supplier was not found");

                $this->dispatcher->forward([
                    'controller' => "Drug_Suppliers",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->supplier_id = $Drug_Supplier->supplier_id;

            $this->tag->setDefault("supplier_id", $Drug_Supplier->supplier_id);
            $this->tag->setDefault("supplier_name", $Drug_Supplier->supplier_name);
            $this->tag->setDefault("supplier_address", $Drug_Supplier->supplier_address);
            $this->tag->setDefault("supplier_landline", $Drug_Supplier->supplier_landline);
            $this->tag->setDefault("supplier_mobile", $Drug_Supplier->supplier_mobile);
            
        }
    }

    /**
     * Creates a new Drug_Supplier
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'index'
            ]);

            return;
        }

        $Drug_Supplier = new DrugSuppliers();
        $Drug_Supplier->supplier_name = $this->request->getPost("supplier_name");
        $Drug_Supplier->supplier_address = $this->request->getPost("supplier_address");
        $Drug_Supplier->supplier_landline = $this->request->getPost("supplier_landline");
        $Drug_Supplier->supplier_mobile = $this->request->getPost("supplier_mobile");
        

        if (!$Drug_Supplier->save()) {
            foreach ($Drug_Supplier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Drug_Supplier was created successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Suppliers",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Drug_Supplier edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'index'
            ]);

            return;
        }

        $supplier_id = $this->request->getPost("supplier_id");
        $Drug_Supplier = DrugSuppliers::findFirstBysupplier_id($supplier_id);

        if (!$Drug_Supplier) {
            $this->flash->error("Drug_Supplier does not exist " . $supplier_id);

            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'index'
            ]);

            return;
        }

        $Drug_Supplier->supplier_name = $this->request->getPost("supplier_name");
        $Drug_Supplier->supplier_address = $this->request->getPost("supplier_address");
        $Drug_Supplier->supplier_landline = $this->request->getPost("supplier_landline");
        $Drug_Supplier->supplier_mobile = $this->request->getPost("supplier_mobile");
        

        if (!$Drug_Supplier->save()) {

            foreach ($Drug_Supplier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'edit',
                'params' => [$Drug_Supplier->supplier_id]
            ]);

            return;
        }

        $this->flash->success("Drug_Supplier was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Suppliers",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Drug_Supplier
     *
     * @param string $supplier_id
     */
    public function deleteAction($supplier_id)
    {
        $Drug_Supplier = DrugSuppliers::findFirstBysupplier_id($supplier_id);
        if (!$Drug_Supplier) {
            $this->flash->error("Drug_Supplier was not found");

            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Drug_Supplier->delete()) {

            foreach ($Drug_Supplier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Suppliers",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Drug_Supplier was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Suppliers",
            'action' => "index"
        ]);
    }

}
