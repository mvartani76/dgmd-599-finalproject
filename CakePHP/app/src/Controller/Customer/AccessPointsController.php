<?php
namespace App\Controller\Customer;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

/**
 * Access Points Controller
 *
 * @property \App\Model\Table\AccessPointsTable $Access Points
 */
class AccessPointsController extends AppController
{
    /**
     * Controller Name
     *
     * @var string
     */
    public $name = 'AccessPoints';

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

    }

    /**
     * View method
     *
     * @param string|null $id Access Point id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    }

    /**
     * Delete method
     *
     * @param string|null $id Access Point id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
    }

    public function company()
    {

    }
}
