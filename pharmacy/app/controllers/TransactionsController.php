<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TransactionsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for Transactions
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Transactions', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "transaction_id";

        $Transactions = Transactions::find($parameters);
        if (count($Transactions) == 0) {
            $this->flash->notice("The search did not find any Transactions");

            $this->dispatcher->forward([
                "controller" => "Transactions",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $Transactions,
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
     * Edits a Transaction
     *
     * @param string $transaction_id
     */
    public function editAction($transaction_id)
    {
        if (!$this->request->isPost()) {

            $Transaction = Transactions::findFirstBytransaction_id($transaction_id);
            if (!$Transaction) {
                $this->flash->error("Transaction was not found");

                $this->dispatcher->forward([
                    'controller' => "Transactions",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->transaction_id = $Transaction->transaction_id;

            $this->tag->setDefault("transaction_id", $Transaction->transaction_id);
            $this->tag->setDefault("cust_id", $Transaction->cust_id);
            $this->tag->setDefault("payment_id", $Transaction->payment_id);
            
        }
    }

    /**
     * Creates a new Transaction
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'index'
            ]);

            return;
        }

        $Transaction = new Transactions();
        $Transaction->cust_id = $this->request->getPost("cust_id");
        $Transaction->payment_id = $this->request->getPost("payment_id");
        

        if (!$Transaction->save()) {
            foreach ($Transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Transaction was created successfully");

        $this->dispatcher->forward([
            'controller' => "Transactions",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a Transaction edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'index'
            ]);

            return;
        }

        $transaction_id = $this->request->getPost("transaction_id");
        $Transaction = Transactions::findFirstBytransaction_id($transaction_id);

        if (!$Transaction) {
            $this->flash->error("Transaction does not exist " . $transaction_id);

            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'index'
            ]);

            return;
        }

        $Transaction->cust_id = $this->request->getPost("cust_id");
        $Transaction->payment_id = $this->request->getPost("payment_id");
        

        if (!$Transaction->save()) {

            foreach ($Transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'edit',
                'params' => [$Transaction->transaction_id]
            ]);

            return;
        }

        $this->flash->success("Transaction was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Transactions",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a Transaction
     *
     * @param string $transaction_id
     */
    public function deleteAction($transaction_id)
    {
        $Transaction = Transactions::findFirstBytransaction_id($transaction_id);
        if (!$Transaction) {
            $this->flash->error("Transaction was not found");

            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'index'
            ]);

            return;
        }

        if (!$Transaction->delete()) {

            foreach ($Transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Transactions",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Transaction was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Transactions",
            'action' => "index"
        ]);
    }

}
