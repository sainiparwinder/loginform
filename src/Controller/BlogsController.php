<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\EventInterface;
/**
 * Blog Controller
 *
 * @method \App\Model\Entity\Blog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogsController extends AppController
{
    public function beforeFilter( $event)
{$this->viewBuilder()->setLayout('blog');
   
  // debug($event);
  
   //   $this->viewBuilder()->setLayout('blog');
}
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function home()
    {
        $this->loadModel('Articles');
        $articles= $this->Articles->find('all');
        $articleList= $this->Articles->find('list',['limit'=>4]);

        $this->set('articles',$this->paginate($articles,['limit'=>3]));
        $this->set('articleList',$articleList);
        
    }
    public function about()
    {
        echo("i am in about");
        
    }
    public function contect()
    {
        echo ("this is my contect page");

    }
    public function view($id)
    {
      
        $this->loadModel('Articles');
        $article=$this->Articles->get($id);
        $this->set('article',$article);
    }

    public function index()
    {    exit('satnam waheguru');
        $blog = $this->paginate($this->Blog);

        $this->set(compact('blog'));
    }

    /**
     * View method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    //public function view($id = null)
    //{
      //  $blog = $this->Blog->get($id, [
        //    'contain' => [],
        //]);

        //$this->set(compact('blog'));
    //}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $blog = $this->Blog->newEmptyEntity();
        if ($this->request->is('post')) {
            $blog = $this->Blog->patchEntity($blog, $this->request->getData());
            if ($this->Blog->save($blog)) {
                $this->Flash->success(__('The blog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog could not be saved. Please, try again.'));
        }
        $this->set(compact('blog'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $blog = $this->Blog->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $blog = $this->Blog->patchEntity($blog, $this->request->getData());
            if ($this->Blog->save($blog)) {
                $this->Flash->success(__('The blog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog could not be saved. Please, try again.'));
        }
        $this->set(compact('blog'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $blog = $this->Blog->get($id);
        if ($this->Blog->delete($blog)) {
            $this->Flash->success(__('The blog has been deleted.'));
        } else {
            $this->Flash->error(__('The blog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
