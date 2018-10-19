<?php
namespace App\Controller\Customer;

use App\Controller\AppController;

/**
 * Apzones Controller
 *
 * @property \App\Model\Table\ApzonesTable $Apzones
 *
 * @method \App\Model\Entity\Apzone[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApzonesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Locations', 'AccessPoints']
        ];
        $apzones = $this->paginate($this->Apzones);

        $this->set(compact('apzones'));
    }

    /**
     * View method
     *
     * @param string|null $id Apzone id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $apzone = $this->Apzones->get($id, [
            'contain' => ['Locations', 'AccessPoints']
        ]);

        $this->set('apzone', $apzone);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $apzone = $this->Apzones->newEntity();
        if ($this->request->is('post')) {
            $apzone = $this->Apzones->patchEntity($apzone, $this->request->getData());
            if ($this->Apzones->save($apzone)) {
                $this->Flash->success(__('The apzone has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The apzone could not be saved. Please, try again.'));
        }
        $locations = $this->Apzones->Locations->find('list', ['limit' => 200]);
        $accessPoints = $this->Apzones->AccessPoints->find('list', ['limit' => 200]);
        $this->set(compact('apzone', 'locations', 'accessPoints'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Apzone id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $apzone = $this->Apzones->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $apzone = $this->Apzones->patchEntity($apzone, $this->request->getData());
            if ($this->Apzones->save($apzone)) {
                $this->Flash->success(__('The apzone has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The apzone could not be saved. Please, try again.'));
        }
        $locations = $this->Apzones->Locations->find('list', ['limit' => 200]);
        $accessPoints = $this->Apzones->AccessPoints->find('list', ['limit' => 200]);
        $this->set(compact('apzone', 'locations', 'accessPoints'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Apzone id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $apzone = $this->Apzones->get($id);
        if ($this->Apzones->delete($apzone)) {
            $this->Flash->success(__('The apzone has been deleted.'));
        } else {
            $this->Flash->error(__('The apzone could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
