<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DrugCouriersController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Drug_Couriers
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'DrugCouriers', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "drug_courier_id";

        $Drug_Couriers = DrugCouriers::find($parameters);
        if (count($Drug_Couriers) == 0) {
            $this->flash->notice("The search did not find any Drug_Couriers");

            $this->dispatcher->forward([
                "controller" => "Drug_Couriers",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Drug_Couriers,
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
     * Edits a Drug_Courier
     *
     * @param string $drug_courier_id
     */
    public function editAction($drug_courier_id)
    {
        if (!$this->request->isPost()) {

            $Drug_Courier = DrugCouriers::findFirstBydrug_courier_id($drug_courier_id);
            if (!$Drug_Courier) {
                $this->flash->error("Drug_Courier was not found");

                $this->dispatcher->forward([
                    'controller' => "Drug_Couriers",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->drug_courier_id = $Drug_Courier->drug_courier_id;

            $this->tag->setDefault("drug_courier_id", $Drug_Courier->drug_courier_id);
            $this->tag->setDefault("drug_ndc_id", $Drug_Courier->drug_ndc_id);
            $this->tag->setDefault("courier_id", $Drug_Courier->courier_id);
            
        }
    }

    /**
     * Creates a new Drug_Courier
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'index'
            ]);

            return;
        }

        $Drug_Courier = new DrugCouriers();
        $Drug_Courier->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        $Drug_Courier->courier_id = $this->request->getPost("courier_id");
        

        if (!$Drug_Courier->save()) {
            foreach ($Drug_Courier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Drug_Courier was created successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Couriers",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Drug_Courier edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'index'
            ]);

            return;
        }

        $drug_courier_id = $this->request->getPost("drug_courier_id");
        $Drug_Courier = DrugCouriers::findFirstBydrug_courier_id($drug_courier_id);

        if (!$Drug_Courier) {
            $this->flash->error("Drug_Courier does not exist " . $drug_courier_id);

            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'index'
            ]);

            return;
        }

        $Drug_Courier->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        $Drug_Courier->courier_id = $this->request->getPost("courier_id");
        

        if (!$Drug_Courier->save()) {

            foreach ($Drug_Courier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'edit',
                'params' => [$Drug_Courier->drug_courier_id]
            ]);

            return;
        }

        $this->flash->success("Drug_Courier was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Couriers",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Drug_Courier
     *
     * @param string $drug_courier_id
     */
    public function deleteAction($drug_courier_id)
    {
        $Drug_Courier = DrugCouriers::findFirstBydrug_courier_id($drug_courier_id);
        if (!$Drug_Courier) {
            $this->flash->error("Drug_Courier was not found");

            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Drug_Courier->delete()) {

            foreach ($Drug_Courier->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drug_Couriers",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Drug_Courier was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Drug_Couriers",
            'action' => "index"
        ]);
    }

}
