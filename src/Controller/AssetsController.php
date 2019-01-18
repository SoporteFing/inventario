<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Imagine;
/**
* Controlador para los activos de la aplicación
*/
class AssetsController extends AppController
{
    public function isAuthorized($user)
    {


        $this->Roles = $this->loadModel('Roles');
        $this->Permissions = $this->loadModel('Permissions');
        $this->RolesPermissions = $this->loadModel('RolesPermissions');

        $allowI = FALSEse;
        $allowM = false;
        $allowE = false;
        $allowC = false;
        
        $query = $this->Roles->find('all', array(
                    'conditions' => array(
                        'id' => $user['id_rol']
                    )
                ))->contain(['Permissions']);

        foreach ($query as $roles) {
            $rls = $roles['permissions'];
            foreach ($rls as $item){
                //$permisos[(int)$item['id']] = 1;
                if($item['nombre'] == 'Insertar Usuarios'){
                    $allowI = true;
                }else if($item['nombre'] == 'Modificar Usuarios'){
                    $allowM = true;
                }else if($item['nombre'] == 'Eliminar Usuarios'){
                    $allowE = true;
                }else if($item['nombre'] == 'Consultar Usuarios'){
                    $allowC = true;
                }
            }
        } 


        $this->set('allowI',$allowI);
        $this->set('allowM',$allowM);
        $this->set('allowE',$allowE);
        $this->set('allowC',$allowC);


        if ($this->request->getParam('action') == 'add'){
            return $allowI;
        }else if($this->request->getParam('action') == 'edit'){
            return $allowM;
        }else if($this->request->getParam('action') == 'softDelete'){
            return $allowE;
        }else if($this->request->getParam('action') == 'view'){
            return $allowC;
        }else{
            return $allowC;
        }


    }
    /**
     * Método para desplegar una lista con un resumen de los datos de activos
     */
    public function index()
    {


        $assets = $this->Assets->find()          
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.year',
                'Locations.nombre',
                'Locations.location_id',
                'Assets.series',
                'Assets.state',
                'Brands.name',
                'Users.nombre',
                'Users.apellido1',
                'Users.apellido2',

            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'users',
        'alias' => 'Users',
        'type' => 'INNER',
        'conditions' => 'Assets.assigned_to = Users.id',
            ])
            ->join([
        'table' => 'locations',
        'alias' => 'Locations',
        'type' => 'INNER',
        'conditions' => 'Assets.location_id = Locations.location_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Models.id_brand = Brands.id',
            ])
            
            ;

            //debug($assets->toList());
            //die();

    $this->set('assets', $this->paginate($assets));


/*
        $this->paginate = [
            'contain' => ['Users', 'Locations','Models','Types']
        ];
        $assets = $this->paginate($this->Assets);
   //      debug($assets);
 //       die();
        $this->set(compact('assets'));
        */
    }
    /**
     * Método para ver los datos completos de un activo
     */
    public function view($id = null)
    {

        $asset = $this->Assets->get($id, [
            'contain' => ['Users', 'Locations', 'Models', 'Types']
        ]);

        $this->set('asset', $asset);
    }
    /**
     * Método para agregar nuevos activos al sistema
     */
    public function add()
    {


        $asset = $this->Assets->newEntity();
        if ($this->request->is('post')) {
            


            $random = uniqid();
            $fecha = date('Y-m-d H:i:s');
            $asset->created = $fecha;
            $asset->modified = $fecha;
            $asset->unique_id = $random;
            $asset->deletable = false;
            $asset->deleted = false;
            $asset->state = "Disponible";
            $asset = $this->Assets->patchEntity($asset, $this->request->getData());


			if ($_POST['models_id'] == '') {
				$asset->models_id = null;
			}

            if ($_POST['brands_id'] != '') {
                $asset->brand = $_POST['brands_id'];
            }


            /** verifica que el id no sea repetido y se setea el error manualmente */
            $returnId = $this->Assets->find('all')
            ->where([
            'Assets.plaque' => $asset->plaque
            ])
            ->first();
            if($returnId){
                $asset->setError('plaque', ['El número de placa ya existe.']);
            }


            if ($this->Assets->save($asset)) {
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue guardado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo guardar, por favor intente nuevamente.'));
        }
        
        $this->loadModel('Brands');
        $brands = $this->Brands->find('list', ['limit' => 200]);

        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
		$types = $this->Assets->Types->find('list', ['limit' => 200]);
        
        $this->set(compact('asset', 'brands', 'users', 'locations', 'models', 'types'));
    }
    /**
     * Método para editar un activo en el sistema
     */
    public function edit($id = null)
    {
        $asset = $this->Assets->get($id, [
            'contain' => []
        ]);


        if ($this->request->is(['patch', 'post', 'put'])) {
            $fecha = date('Y-m-d H:i:s');
            $asset->modified = $fecha;
            
            $asset = $this->Assets->patchEntity($asset, $this->request->getData());
			if ($_POST['models_id'] == '') {
				$asset->models_id = null;
			}
            if ($this->Assets->save($asset)) {
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue guardado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }
            debug($asset);
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo guardar, por favor intente nuevamente.'));
        }

        $this->loadModel('Brands');

        $brands = $this->Brands->find('list')          
            ->select([
                'Brands.id',
                'Brands.name'
            ])
            ->distinct(['Brands.name'])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'RIGHT',
        'conditions' => 'Models.id_brand = Brands.id',
            ])
            ->where(['Models.id_type' => $asset->type_id]);

        $this->loadModel('Models');
        $models = $this->Models->find('list')
            ->where(['Models.id_brand' => $asset->brand]);

        
        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
		$types = $this->Assets->Types->find('list', ['limit' => 200]);
		
        $this->set(compact('asset', 'brands', 'users', 'locations', 'models', 'types'));

    }


    /**
     * Restaura un activo desactivado
     */
    public function restore($plaque){
        $asset = $this->Assets->get($plaque);
        $asset->deleted = false;
        $asset->state = 'Disponible';
        $fecha = date('Y-m-d H:i:s');
        $asset->modified = $fecha;

        if ($this->Assets->save($asset)) {
            $this->Flash->success(__('El activo fue activado exitosamente.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('El activo no se pudo activar correctamente.'));
        return $this->redirect(['action' => 'index']);
    }


    /**
     * Elimina solo logicamente los activos de la base de datos
     * 
     * @param asset
     * @return 0 - archivo no se eliminó correctamente, 1 - hard delete completado, 2 - soft delete completado
     */
    public function softDelete($plaque){
        $asset = $this->Assets->get($plaque);
        
        if($asset->deletable){
            if($this->Assets->delete($asset)){
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue eliminado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo eliminar correctamente.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $fecha = date('Y-m-d H:i:s');
        $asset->deleted = true;
        $asset->state = 'Desactivado';
        $asset->modified = $fecha;
        
        if ($this->Assets->save($asset)) {
            $this->Flash->success(__('El activo fue desactivado exitosamente.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('El activo no se pudo desactivar correctamente.'));
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Método para eliminar un activo del sistema
     */
    public function delete($asset)
    {
        /* 
        DEPRECATED 
            if ($this->Assets->delete($asset)) {
                return 1;
            } else {
                return 0;
            }
        */
    }

    /**
     * Método para mostrar listas dependientes
     */
    public function brandsList()
    {
        $this->loadModel('Models');
        $this->loadModel('Brands');
        $this->loadModel('Types');

        $type_id = $_GET['type_id'];
        
        $type = $this->Types->get($type_id);

        if($type == NULL)
        {
            throw new NotFoundException(__('Tipo no encontrado') );      
        }
        
        $brands = $this->Brands->find('list')          
            ->select([
                'Brands.id',
                'Brands.name'
            ])
            ->distinct(['Brands.name'])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'RIGHT',
        'conditions' => 'Models.id_brand = Brands.id',
            ])
            ->where(['Models.id_type' => $type_id]);

        if(empty($brands))
        {
            throw new NotFoundException(__('Marcas no encontradas') );      
        }

        $this->set('brands', $brands);

        /*Asocia esta función a la vista /Templates/Layout/model_list.ctp*/
        $this->render('/Layout/brand_list');
    }
    
    /**
     * Método para mostrar listas dependientes
     */
    public function modelsList()
    {
        $this->loadModel('Models');
        $this->loadModel('Brands');
        
        $brand_id = $_GET['brand_id'];
        
        $brand = $this->Brands->get($brand_id);
        if($brand == NULL)
        {
            throw new NotFoundException(__('Marca no encontrada') );      
        }
        
        $models = $this->Models->find('list')
            ->where(['Models.id_brand' => $brand->id]);
        
        if(empty($models))
        {
            throw new NotFoundException(__('Modelos no encontrados') );      
        }
        $this->set('models', $models);
        /*Asocia esta función a la vista /Templates/Layout/model_list.ctp*/
        $this->render('/Layout/model_list');
    }
    



    /**
     * Método para agregar activos por lotes
     */
    public function batch($cantidad = null)
    {
        $asset = $this->Assets->newEntity();
        //$asset = $this->Assets->newEntity();
        if ($this->request->is('post')) {
            //guarda en variables todos los campos reutilizables
            $cantidad = $this->request->getData('quantity');
            $placa = $this->request->getData('plaque');
            $marca = $this->request->getData('brand');
            $modelo = $this->request->getData('models_id');
            $tipo = $this->request->getData('type_id');
			//$type = $this->request->getData('type_id');
            if ($_POST['brand'] == '') {
                $marca = null;
            } else {
                $marca = $this->request->getData('brand');
            }
            
			if ($_POST['models_id'] == '') {
				$modelo = null;
			} else {
				$modelo = $this->request->getData('models_id');
			}
            $descripcion = $this->request->getData('description');
            $dueno = $this->request->getData('owner_id');
            $responsable = $this->request->getData('responsable_id');
            $asignado = $this->request->getData('assigned_to');
            $ubicacion = $this->request->getData('location_id');
            $subUbicacion = $this->request->getData('sub_location');
            $año = $this->request->getData('year');
            $prestable = $this->request->getData('lendable');
            $observaciones = $this->request->getData('observations');
            $imagen = $this->request->getData('image');
            $archivo = $this->request->getData('file');
            $series = $this->request->getData('series');
            $listaSeries = preg_split("/(, )| |,/", $series, -1);
            //parseo la placa con letras para dividirla en predicado+numero (asg21fa34)
            //divide con una expresion regular: (\d*)$
            //pregunta si hay letras en la placa
            if (preg_match("/([a-z])\w+/", $placa)){
                list($predicado, $numero) = preg_split("/(\d*)$/", $placa, NULL ,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            }
            //$predicado = asg21fa
            //$numero = 34
            //realiza el ciclo
            for ($i = 0; $i < $cantidad; $i++){
                $asset = $this->Assets->newEntity();
                if (array_key_exists($i, $listaSeries)){
                        $serie = $listaSeries[$i];
                    } else{
                        $serie = null;
                    }
                $random = uniqid();
                $fecha = date('Y-m-d H:i:s');
                $asset->created = $fecha;
                $asset->modified = $fecha;
                $asset->unique_id = $random;
                $asset->deletable = true;
                $asset->deleted = false;
                $asset->state = "Disponible";
                if(!preg_match("/([a-z])\w+/", $placa)){ //pregunto si las placas solo son de numeros
                    $data = [
                        'plaque' => $placa,
                        'brand' => $marca,
                        'models_id' => $modelo,
                        'type_id' => $tipo,
                        'series' => $serie,
                        'description' => $descripcion,
                        'owner_id' => $dueno,
                        'responsable_id' => $responsable,
                        'assigned_to' => $asignado,
                        'location_id' => $ubicacion,
                        'sub_location' => $subUbicacion, 
                        'year' => $año,
                        'lendable' => $prestable,
                        'observations' => $observaciones,
                        'image' => $imagen,
                        'file' => $archivo
                    ];
                    //incrementa la placa
                    $placa = $placa + 1;
                }
                else{ //entonces las placas son alfanumericas, agrego predicado+numero como placa
                    $data = [
                        'plaque' => $predicado . $numero,
                        'brand' => $marca,
                        'models_id' => $modelo,
                        'type_id' => $tipo,
                        'series' => $serie,
                        'description' => $descripcion,
                        'owner_id' => $dueno,
                        'responsable_id' => $responsable,
                        'assigned_to' => $asignado,
                        'location_id' => $ubicacion, 
                        'sub_location' => $subUbicacion, 
                        'year' => $año,
                        'lendable' => $prestable,
                        'observations' => $observaciones,
                        'image' => $imagen,
                        'file' => $archivo
                    ];
                    //incrementa la placa
                    $numero = $numero + 1;
                }
                
                $asset = $this->Assets->patchEntity($asset, $data);
                //meter una por una a la base
                $this->Assets->save($asset);
            }
            $this->Flash->success(__('Los activos fueron guardados'));
            return $this->redirect(['action' => 'index']);
        }
        $this->loadModel('Brands');
        $brands = $this->Brands->find('list', ['limit' => 200]);
        //$types = $this->Assets->Types->find('list', ['limit' => 200]);
        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
        $types = $this->Assets->Types->find('list', ['limit' => 200]);
        $this->set(compact('asset', 'types', 'brands', 'users', 'locations','models'));

    }
}