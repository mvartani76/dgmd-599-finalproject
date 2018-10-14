<?php
namespace App\Controller\Customer;

use App\Controller\AppController;

/**
 * ScanResults Controller
 *
 * @property \App\Model\Table\ScanResultsTable $ScanResults
 *
 * @method \App\Model\Entity\ScanResult[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ScanResultsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['access_points']
        ];
        $scanResults = $this->paginate($this->ScanResults);

        $this->set(compact('scanResults'));
    }

    /**
     * View method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scanResult = $this->ScanResults->get($id, [
            'contain' => ['access_points']
        ]);

        $this->set('scanResult', $scanResult);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scanResult = $this->ScanResults->newEntity();
        if ($this->request->is('post')) {
            $scanResult = $this->ScanResults->patchEntity($scanResult, $this->request->getData());
            if ($this->ScanResults->save($scanResult)) {
                $this->Flash->success(__('The scan result has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scan result could not be saved. Please, try again.'));
        }
        $accesspoints = $this->ScanResults->AccessPoints->find('list', ['limit' => 200]);
        $this->set(compact('scanResult', 'accesspoints'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scanResult = $this->ScanResults->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scanResult = $this->ScanResults->patchEntity($scanResult, $this->request->getData());
            if ($this->ScanResults->save($scanResult)) {
                $this->Flash->success(__('The scan result has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scan result could not be saved. Please, try again.'));
        }
        $accesspoints = $this->ScanResults->AccessPoints->find('list', ['limit' => 200]);
        $this->set(compact('scanResult', 'accesspoints'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scanResult = $this->ScanResults->get($id);
        if ($this->ScanResults->delete($scanResult)) {
            $this->Flash->success(__('The scan result has been deleted.'));
        } else {
            $this->Flash->error(__('The scan result could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
