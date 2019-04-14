<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DrugsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Drugs
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Drugs', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "drug_ndc_id";

        $Drugs = Drugs::find($parameters);
        if (count($Drugs) == 0) {
            $this->flash->notice("The search did not find any Drugs");

            $this->dispatcher->forward([
                "controller" => "Drugs",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Drugs,
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
     * Edits a Drug
     *
     * @param string $drug_ndc_id
     */
    public function editAction($drug_ndc_id)
    {
        if (!$this->request->isPost()) {

            $Drug = Drugs::findFirstBydrug_ndc_id($drug_ndc_id);
            if (!$Drug) {
                $this->flash->error("Drug was not found");

                $this->dispatcher->forward([
                    'controller' => "Drugs",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->drug_ndc_id = $Drug->drug_ndc_id;

            $this->tag->setDefault("drug_ndc_id", $Drug->drug_ndc_id);
            $this->tag->setDefault("drug_commercial_name", $Drug->drug_commercial_name);
            $this->tag->setDefault("drug_scientific_name", $Drug->drug_scientific_name);
            $this->tag->setDefault("drug_company", $Drug->drug_company);
            $this->tag->setDefault("drug_supplier_id", $Drug->drug_supplier_id);
            $this->tag->setDefault("drug_in_inventory", $Drug->drug_in_inventory);
            $this->tag->setDefault("drug_in_warehouse", $Drug->drug_in_warehouse);
            
        }
    }

    /**
     * Creates a new Drug
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'index'
            ]);

            return;
        }

        $Drug = new Drugs();
        $Drug->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        $Drug->drug_commercial_name = $this->request->getPost("drug_commercial_name");
        $Drug->drug_scientific_name = $this->request->getPost("drug_scientific_name");
        $Drug->drug_company = $this->request->getPost("drug_company");
        $Drug->drug_supplier_id = $this->request->getPost("drug_supplier_id");
        $Drug->drug_in_inventory = $this->request->getPost("drug_in_inventory");
        $Drug->drug_in_warehouse = $this->request->getPost("drug_in_warehouse");
        

        if (!$Drug->save()) {
            foreach ($Drug->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Drug was created successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Drug edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'index'
            ]);

            return;
        }

        $drug_ndc_id = $this->request->getPost("drug_ndc_id");
        $Drug = Drugs::findFirstBydrug_ndc_id($drug_ndc_id);

        if (!$Drug) {
            $this->flash->error("Drug does not exist " . $drug_ndc_id);

            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'index'
            ]);

            return;
        }

        $Drug->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        $Drug->drug_commercial_name = $this->request->getPost("drug_commercial_name");
        $Drug->drug_scientific_name = $this->request->getPost("drug_scientific_name");
        $Drug->drug_company = $this->request->getPost("drug_company");
        $Drug->drug_supplier_id = $this->request->getPost("drug_supplier_id");
        $Drug->drug_in_inventory = $this->request->getPost("drug_in_inventory");
        $Drug->drug_in_warehouse = $this->request->getPost("drug_in_warehouse");
        

        if (!$Drug->save()) {

            foreach ($Drug->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'edit',
                'params' => [$Drug->drug_ndc_id]
            ]);

            return;
        }

        $this->flash->success("Drug was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Drug
     *
     * @param string $drug_ndc_id
     */
    public function deleteAction($drug_ndc_id)
    {
        $Drug = Drugs::findFirstBydrug_ndc_id($drug_ndc_id);
        if (!$Drug) {
            $this->flash->error("Drug was not found");

            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Drug->delete()) {

            foreach ($Drug->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Drug was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs",
            'action' => "index"
        ]);
    }

}
