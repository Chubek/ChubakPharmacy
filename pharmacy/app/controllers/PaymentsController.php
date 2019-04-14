<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PaymentsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Payments
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Payments', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "payment_id";

        $Payments = Payments::find($parameters);
        if (count($Payments) == 0) {
            $this->flash->notice("The search did not find any Payments");

            $this->dispatcher->forward([
                "controller" => "Payments",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Payments,
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
     * Edits a Payment
     *
     * @param string $payment_id
     */
    public function editAction($payment_id)
    {
        if (!$this->request->isPost()) {

            $Payment = Payments::findFirstBypayment_id($payment_id);
            if (!$Payment) {
                $this->flash->error("Payment was not found");

                $this->dispatcher->forward([
                    'controller' => "Payments",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->payment_id = $Payment->payment_id;

            $this->tag->setDefault("payment_id", $Payment->payment_id);
            $this->tag->setDefault("payment_date", $Payment->payment_date);
            $this->tag->setDefault("payment_method", $Payment->payment_method);
            $this->tag->setDefault("payment_amount", $Payment->payment_amount);
            
        }
    }

    /**
     * Creates a new Payment
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'index'
            ]);

            return;
        }

        $Payment = new Payments();
        $Payment->payment_date = $this->request->getPost("payment_date");
        $Payment->payment_method = $this->request->getPost("payment_method");
        $Payment->payment_amount = $this->request->getPost("payment_amount");
        

        if (!$Payment->save()) {
            foreach ($Payment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Payment was created successfully");

        $this->dispatcher->forward([
            'controller' => "Payments",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Payment edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'index'
            ]);

            return;
        }

        $payment_id = $this->request->getPost("payment_id");
        $Payment = Payments::findFirstBypayment_id($payment_id);

        if (!$Payment) {
            $this->flash->error("Payment does not exist " . $payment_id);

            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'index'
            ]);

            return;
        }

        $Payment->payment_date = $this->request->getPost("payment_date");
        $Payment->payment_method = $this->request->getPost("payment_method");
        $Payment->payment_amount = $this->request->getPost("payment_amount");
        

        if (!$Payment->save()) {

            foreach ($Payment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'edit',
                'params' => [$Payment->payment_id]
            ]);

            return;
        }

        $this->flash->success("Payment was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Payments",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Payment
     *
     * @param string $payment_id
     */
    public function deleteAction($payment_id)
    {
        $Payment = Payments::findFirstBypayment_id($payment_id);
        if (!$Payment) {
            $this->flash->error("Payment was not found");

            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Payment->delete()) {

            foreach ($Payment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Payments",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Payment was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Payments",
            'action' => "index"
        ]);
    }

}
