<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DrugsInPerscriptionController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Drugs_in_Perscription
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'DrugsInPerscription', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "dr_pr_id";

        $Drugs_in_Perscription = DrugsInPerscription::find($parameters);
        if (count($Drugs_in_Perscription) == 0) {
            $this->flash->notice("The search did not find any Drugs_in_Perscription");

            $this->dispatcher->forward([
                "controller" => "Drugs_in_Perscription",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Drugs_in_Perscription,
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
     * Edits a Drugs_in_Perscription
     *
     * @param string $dr_pr_id
     */
    public function editAction($dr_pr_id)
    {
        if (!$this->request->isPost()) {

            $Drugs_in_Perscription = DrugsInPerscription::findFirstBydr_pr_id($dr_pr_id);
            if (!$Drugs_in_Perscription) {
                $this->flash->error("Drugs_in_Perscription was not found");

                $this->dispatcher->forward([
                    'controller' => "Drugs_in_Perscription",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->dr_pr_id = $Drugs_in_Perscription->dr_pr_id;

            $this->tag->setDefault("dr_pr_id", $Drugs_in_Perscription->dr_pr_id);
            $this->tag->setDefault("persc_id", $Drugs_in_Perscription->persc_id);
            $this->tag->setDefault("drug_ndc_id", $Drugs_in_Perscription->drug_ndc_id);
            
        }
    }

    /**
     * Creates a new Drugs_in_Perscription
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'index'
            ]);

            return;
        }

        $Drugs_in_Perscription = new DrugsInPerscription();
        $Drugs_in_Perscription->persc_id = $this->request->getPost("persc_id");
        $Drugs_in_Perscription->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        

        if (!$Drugs_in_Perscription->save()) {
            foreach ($Drugs_in_Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Drugs_in_Perscription was created successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs_in_Perscription",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Drugs_in_Perscription edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'index'
            ]);

            return;
        }

        $dr_pr_id = $this->request->getPost("dr_pr_id");
        $Drugs_in_Perscription = DrugsInPerscription::findFirstBydr_pr_id($dr_pr_id);

        if (!$Drugs_in_Perscription) {
            $this->flash->error("Drugs_in_Perscription does not exist " . $dr_pr_id);

            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'index'
            ]);

            return;
        }

        $Drugs_in_Perscription->persc_id = $this->request->getPost("persc_id");
        $Drugs_in_Perscription->drug_ndc_id = $this->request->getPost("drug_ndc_id");
        

        if (!$Drugs_in_Perscription->save()) {

            foreach ($Drugs_in_Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'edit',
                'params' => [$Drugs_in_Perscription->dr_pr_id]
            ]);

            return;
        }

        $this->flash->success("Drugs_in_Perscription was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs_in_Perscription",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Drugs_in_Perscription
     *
     * @param string $dr_pr_id
     */
    public function deleteAction($dr_pr_id)
    {
        $Drugs_in_Perscription = DrugsInPerscription::findFirstBydr_pr_id($dr_pr_id);
        if (!$Drugs_in_Perscription) {
            $this->flash->error("Drugs_in_Perscription was not found");

            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Drugs_in_Perscription->delete()) {

            foreach ($Drugs_in_Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Drugs_in_Perscription",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Drugs_in_Perscription was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Drugs_in_Perscription",
            'action' => "index"
        ]);
    }

}
