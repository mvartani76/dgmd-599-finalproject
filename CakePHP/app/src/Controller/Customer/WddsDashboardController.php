<?php


namespace App\Controller\Customer;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\DashboardController as NonCustomerDashboardController;
use Tools\Model\Table\Table;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class WddsDashboardController extends NonCustomerDashboardController
{

    public function index() {
        $this->loadModel('Dashboards');

        $dashboard = $this->Dashboards->findOrCreate([
            'user_id' => $this->Auth->user('id'),
            'customer_id'=> $this->Auth->user('customer_id')
        ], [$this->Dashboards, 'createDefaultDashboard']);

        if (empty($dashboard->dashboard_widgets)) {
            $dashboard->dashboard_widgets = $this->Dashboards->DashboardWidgets->findByDashboardId($dashboard->id)
                ->order('DashboardWidgets.sort')->toArray();
        }
        $dashboardSettings = Configure::read('Dashboard');

        $this->set('dashboardSettings', $dashboardSettings);
        $this->set('dashboard', $dashboard);


        $this->viewBuilder()->layout('default');

        $accessPoints = TableRegistry::get('AccessPoints');
        
        $accessPointsCount = $accessPoints
            ->find('all', [

            ])
            ->where([         ])
            ->count();

        $this->set(compact('accessPointsCount'));
    }

    public function deleteDashboard($user_id,$customer_id) {
        $this->loadModel('Dashboards');
        $this->loadModel('DashboardWidgets');
        $conn = ConnectionManager::get('default');
        $conn->begin();
        if(!$this->DashboardWidgets->deleteAll(['user_id'=> $user_id, 'customer_id'=>$customer_id])) {
            $conn->rollback();
            return false;
        }
        if(!$this->Dashboards->deleteAll(['user_id'=> $user_id, 'customer_id'=>$customer_id])) {
            $conn->rollback();
            return false;
        }
        $conn->commit();
        return true;
    }

    public function resetDashboard() {
        $user_id = $this->Auth->user('id');
        $customer_id = $this->Auth->user('customer_id');
        if($this->deleteDashboard($user_id,$customer_id)) {
            $this->Flash->calloutFlash(
                'The dashboard has been reseted.', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'Success',
                    'class' => 'callout-success',
                    'fa' => 'check'
                ]
            ]);
        } else {
            $this->Flash->calloutFlash(
                'The dashboard could not be reseted. Please, try again.', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'Error',
                    'class' => 'callout-danger',
                    'fa' => 'excl'
                ]
            ]);
        }
        return $this->redirect(['action' => 'index']);
    }
}