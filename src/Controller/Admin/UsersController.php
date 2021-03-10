<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{   
    public $fileName;
    public $fileExt;

    public function initialize(): void
    { 
        parent::initialize();
        $this->set('email',$this->Auth->user('email'));

        //$this->Auth->allow(['login','index']); 
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    { 
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            //pr($user); die;
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect(['controller'=>'Users','action'=>'index']);
            } else {
                $this->Flash->error(__('Username or password is incorrect'));
            }
        }
    }
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
    public function index()
    {   
        $key = $this->request->getQuery('key');
        if($key){
            $query=$this->Users->find('all')->where(['or'=>['email like'=>'%'.$key.'%']]);
        }else{
            $query=$this->Users;
        }
        $users = $this->paginate($query,['contain'=>['Profiles','Skills']]);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Articles'],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
           //($user);exit;
            
            $file = $this->request->getData('image_file');
            
            $clientFileName = $file->getClientFilename();
            $fileSize = $file->getSize();
            
                //exit('helllo');
            $this->fileExt = pathinfo($clientFileName, PATHINFO_EXTENSION);

            $this->fileName = uniqid() . "." . $this->fileExt;
            $imagePath = WWW_ROOT .'img'.DS. 'images/' . $this->fileName;
            //exit($imagePath);
            if (!is_writable(WWW_ROOT .'img'.DS. 'images')) { 
                //exit('hello');             
                if (!file_exists(WWW_ROOT .'img'.DS.'images')) {
                    mkdir(WWW_ROOT .'img'.DS.'images');
                }         
                chmod(WWW_ROOT .'img'.DS.'images', 0777);
            }
            if ($fileSize <= 50320921) {
                if (in_array(strtolower($this->fileExt), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $file->moveTo($imagePath);
                        $user->image='images/'.$this->fileName;
                }else{
                    $this->Flash->error(__("Only JPG, JPEG, PNG files are allowed."));
                }
            }else{
                $this->Flash->error(__('Sorry, the file is too large.'));
            } 
          
            

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            $file = $this->request->getData('change_image');
            
            $clientFileName = $file->getClientFilename();
            $fileSize = $file->getSize();
            
                //exit('helllo');
            $this->fileExt = pathinfo($clientFileName, PATHINFO_EXTENSION);

            $this->fileName = uniqid() . "." . $this->fileExt;
            $imagePath = WWW_ROOT .'img'.DS. 'images/' . $this->fileName;
            //exit($imagePath);
            if (!is_writable(WWW_ROOT .'img'.DS. 'images')) { 
                //exit('hello');             
                if (!file_exists(WWW_ROOT .'img'.DS.'images')) {
                    mkdir(WWW_ROOT .'img'.DS.'images');
                }         
                chmod(WWW_ROOT .'img'.DS.'images', 0777);
            }
            if ($fileSize <= 50320921) {
                if (in_array(strtolower($this->fileExt), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $file->moveTo($imagePath);
                        $imagePath= WWW_ROOT.'img'.DS.$user->image;
                        // pr($imagePath);
                         if(file_exists($imagePath)){
                             unlink($imagePath);
                        $user->image='images/'.$this->fileName;

                }else{
                    $this->Flash->error(__("Only JPG, JPEG, PNG files are allowed."));
                }
            }else{
                $this->Flash->error(__('Sorry, the file is too large.'));
            } 
          
            




            
        }





            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }
    public function deleteAll(){
        $ids=$this->request->getData('ids');
        if($this->Users->deleteAll(['Users.id IN'=>$ids])){
           $this->Flash->success(__('The users has been deleted.'));  
        }
        return $this->redirect(['action' => 'index']);

    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $imagePath= WWW_ROOT.'img'.DS.$user->image;
       // pr($imagePath);
        if(file_exists($imagePath)){
            unlink($imagePath);
        //exit($imagePath);
    }
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function userStatus($id=null,$status)
    {
        $user = $this->Users->get($id);
       if($status==1)
       $user->status=0;
       else
       $user->status=1;
       if ($this->Users->save($user)) {
        $this->Flash->success(__('The user has change status.'));

        return $this->redirect(['action' => 'index']);
    }

       exit;
    }
}
