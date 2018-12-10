<?php
namespace App\Controller\Marketing;

use App\Controller\AppController;

/**
 * Heatmaps Controller
 *
 * @property \App\Model\Table\HeatmapsTable $Heatmaps
 *
 * @method \App\Model\Entity\Heatmap[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HeatmapsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['access_points', 'floorplans_library']
        ];
        $heatmaps = $this->paginate($this->Heatmaps);

        $this->set(compact('heatmaps'));
    }

    /**
     * View method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => ['access_points', 'floorplans_library']
        ]);

        $this->set('heatmap', $heatmap);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $heatmap = $this->Heatmaps->newEntity();
        if ($this->request->is('post')) {
            $heatmap = $this->Heatmaps->patchEntity($heatmap, $this->request->getData());
            if ($this->Heatmaps->save($heatmap)) {
                $this->Flash->success(__('The heatmap has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The heatmap could not be saved. Please, try again.'));
        }
        $accessPoints = $this->Heatmaps->AccessPoints->find('list', ['limit' => 200]);
        $floorplansLibrary = $this->Heatmaps->FloorplansLibrary->find('list', ['limit' => 200]);
        $this->set(compact('heatmap', 'accessPoints', 'floorplansLibrary'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $heatmap = $this->Heatmaps->patchEntity($heatmap, $this->request->getData());
            if ($this->Heatmaps->save($heatmap)) {
                $this->Flash->success(__('The heatmap has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The heatmap could not be saved. Please, try again.'));
        }
        $accessPoints = $this->Heatmaps->access_points->find('list', ['limit' => 200]);
        $floorplans = $this->Heatmaps->floorplans_library->find('list', ['limit' => 200]);
        $this->set(compact('heatmap', 'accessPoints', 'floorplans'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $heatmap = $this->Heatmaps->get($id);
        if ($this->Heatmaps->delete($heatmap)) {
            $this->Flash->success(__('The heatmap has been deleted.'));
        } else {
            $this->Flash->error(__('The heatmap could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function preview($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => ['access_points', 'floorplans_library']
        ]);

        $this->set('heatmap', $heatmap);
    }

    /**
     * Show Activity method
     *
     * @return \Cake\Http\Response|void
     */
    public function showactivity()
    {
        $this->paginate = [
            'contain' => ['access_points', 'floorplans_library']
        ];
        $heatmaps = $this->paginate($this->Heatmaps);

        $this->set(compact('heatmaps'));
    }
}
