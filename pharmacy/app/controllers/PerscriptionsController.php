<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PerscriptionsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Perscriptions
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Perscriptions', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "persc_id";

        $Perscriptions = Perscriptions::find($parameters);
        if (count($Perscriptions) == 0) {
            $this->flash->notice("The search did not find any Perscriptions");

            $this->dispatcher->forward([
                "controller" => "Perscriptions",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Perscriptions,
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
     * Edits a Perscription
     *
     * @param string $persc_id
     */
    public function editAction($persc_id)
    {
        if (!$this->request->isPost()) {

            $Perscription = Perscriptions::findFirstBypersc_id($persc_id);
            if (!$Perscription) {
                $this->flash->error("Perscription was not found");

                $this->dispatcher->forward([
                    'controller' => "Perscriptions",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->persc_id = $Perscription->persc_id;

            $this->tag->setDefault("persc_id", $Perscription->persc_id);
            $this->tag->setDefault("persc_date", $Perscription->persc_date);
            $this->tag->setDefault("persc_jpeg", $Perscription->persc_jpeg);
            
        }
    }

    /**
     * Creates a new Perscription
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'index'
            ]);

            return;
        }

        $Perscription = new Perscriptions();
        $Perscription->persc_date = $this->request->getPost("persc_date");
        $Perscription->persc_jpeg = $this->request->getPost("persc_jpeg");
        

        if (!$Perscription->save()) {
            foreach ($Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Perscription was created successfully");

        $this->dispatcher->forward([
            'controller' => "Perscriptions",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Perscription edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'index'
            ]);

            return;
        }

        $persc_id = $this->request->getPost("persc_id");
        $Perscription = Perscriptions::findFirstBypersc_id($persc_id);

        if (!$Perscription) {
            $this->flash->error("Perscription does not exist " . $persc_id);

            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'index'
            ]);

            return;
        }

        $Perscription->persc_date = $this->request->getPost("persc_date");
        $Perscription->persc_jpeg = $this->request->getPost("persc_jpeg");
        

        if (!$Perscription->save()) {

            foreach ($Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'edit',
                'params' => [$Perscription->persc_id]
            ]);

            return;
        }

        $this->flash->success("Perscription was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Perscriptions",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Perscription
     *
     * @param string $persc_id
     */
    public function deleteAction($persc_id)
    {
        $Perscription = Perscriptions::findFirstBypersc_id($persc_id);
        if (!$Perscription) {
            $this->flash->error("Perscription was not found");

            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Perscription->delete()) {

            foreach ($Perscription->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Perscriptions",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Perscription was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Perscriptions",
            'action' => "index"
        ]);
    }

}
