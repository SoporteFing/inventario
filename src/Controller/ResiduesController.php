<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

use Dompdf\Dompdf;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;


//use Cake\ORM\Query;

/**
 * Residues Controller
 *
 * @property \App\Model\Table\ResiduesTable $Residues
 *
 * @method \App\Model\Entity\Residue[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResiduesController extends AppController
{
    private $UnidadAcadémica = 'Ingeniería';

    public function isAuthorized($user)
    {

        $this->Roles = $this->loadModel('Roles');
        $this->Permissions = $this->loadModel('Permissions');
        $this->RolesPermissions = $this->loadModel('RolesPermissions');

        $allowI = false;
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
        }else if($this->request->getParam('action') == 'delete'){
            return $allowE;
        }else if($this->request->getParam('action') == 'view'){
            return $allowC;
        }else{
            return $allowC;
        }


    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {

        $residues = $this->paginate($this->Residues);   
        $Unidad = $this->UnidadAcadémica;
        $this->set(compact('residues','Unidad'));
    }

    /**
     * View method
     *
     * @param string|null $id Residue id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $residue = $this->Residues->get($id);

        //obtengo la tabla assets
        $assets = TableRegistry::get('Assets');
        //busco los datos que necesito
        $assetsquery = $assets->find()
                        ->select(['assets.plaque'])
                        ->where(['residues_id'=>$id])
                        ->toList();

        //lo paso a objeto para manejarlo en vista
        $size = count($assetsquery);
        $result = array_fill(0, $size, NULL);
        
        for($i = 0; $i < $size; $i++)
        {
            $result[$i] =(object)$assetsquery[$i]->assets;
        }

        //obtengo la tabla technical_reports
        $technical_reports = TableRegistry::get('TechnicalReports');
        //busco los datos que necesito
        $queryRec = $technical_reports->find()
                                    ->select(['recommendation', 'technical_report_id'])
                                    ->where(['residues_id'=>$id])
                                    ->group(['assets_id'])
                                    ->toList();

        //lo paso a objeto para manejarlo en vista
        $size = count($queryRec);

        $resultRec = array_fill(0, $size, NULL);
        for($i = 0; $i < $size; $i++)
        {
            // se realiza una conversion a objeto para que la vista lo use sin problemas
            $resultRec[$i]= (object)$queryRec[$i];
        }

        $Unidad = $this->UnidadAcadémica;

        $this->set(compact('residue', 'result', 'resultRec', 'Unidad'));

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        
        $residue = $this->Residues->newEntity();
        if ($this->request->is('post')) {

            $residue = $this->Residues->patchEntity($residue, $this->request->getData());

            // le doy formato a la fecha para que mysql pueda guardarla correctamente

            if($residue->date != null)
            {
                $date = new Date($residue->date);
                $residue->date= $date->format('Y-m-d');
            }

            /** varifica que el id no sea repetido y se setea el error manualmente */
            $returnId = $this->Residues->find('all')
            ->where([
            'Residues.residues_id' => $residue->residues_id
            ])
            ->first();
            if($returnId){
                $residue->setError('residues_id', ['El número de acta ya existe.']);
            }


            $residue['new']=true;
            if ($this->Residues->save($residue) ) {

                

                //Se obtienen los seleccionados y se convierte a string separado en , 
                $condicion = explode(',', $this->request->getData('checkList'));
                
                //Actualiza  los Activos seleccionados
                $assets = TableRegistry::get('Assets')->find('all');
                $assets->update()
                    ->set(['residues_id' => $residue->residues_id, 'state' => "Desechado"])
                    ->where(['plaque IN' => $condicion])
                    ->execute();

                //Actualiza los reportes technicos donde tengan los Activos seleccionados
                $technical_reports = TableRegistry::get('TechnicalReports')->find('all');
                $technical_reports->update()
                    ->set(['residues_id' => $residue->residues_id])
                    ->where(['assets_id IN' => $condicion])
                    ->execute();
                    AppController::insertLog($residue['residues_id'], TRUE);
                $this->Flash->success(__('El acta de desecho fue guardada.'));
                return $this->redirect(['action' => 'viewDownload', $residue->residues_id ] );
            }
            AppController::insertLog($model['residues_id'], FALSE);
            $this->Flash->error(__('El Acta de Desecho no se pudo guardar. Inténtelo de nuevo.'));
        }


        //Hace la seleccion de los Activos usando Join para unir los datos
        $technical_reports = TableRegistry::get('TechnicalReports');
        $assetsQuery = $technical_reports->find()
                         ->select(['assets.plaque','brands.name','models.name','assets.series','assets.state'])
                         ->join([
                            'assets' => [
                                    'table' => 'assets',
                                    'type'  => 'LEFT',
                                    'conditions' => ['assets.plaque= TechnicalReports.assets_id']
                                ]
                                ])
                         ->join([
                            'models' => [
                                    'table' => 'models',
                                    'type'  => 'LEFT',
                                    'conditions' => ['assets.models_id= models.id']
                                ]
                                ])
                         ->join([
                            'brands' => [
                                    'table' => 'brands',
                                    'type'  => 'LEFT',
                                    'conditions' => ['models.id_brand = brands.id']
                                ]
                                ])
                         ->where(['TechnicalReports.recommendation' => "D", 'assets.state' => "Disponible"])
                         ->group (['assets.plaque'])
                         ->toList();

        $size = count($assetsQuery);
        $result=   array_fill(0, $size, NULL);
        
        for($i=0;$i<$size;$i++)
        {
            //* se acomodan los valores dentro de un mismo [$i]
            $result[$i]['plaque']= $assetsQuery[$i]->assets['plaque'];
            $result[$i]['brand']= $assetsQuery[$i]->brands['name'];
            $result[$i]['model']= $assetsQuery[$i]->models['name'];
            $result[$i]['series']= $assetsQuery[$i]->assets['series'];
            $result[$i]['state']= $assetsQuery[$i]->assets['state'];

            // se realiza una conversion a objeto para que la vista lo use sin problemas
            $result[$i]= (object)$result[$i];
        }

        debug($result);
        die();

        $this->set(compact('residue', 'result'));
    }
    /**
     * Edit method
     *
     * @param string|null $id Residue id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $residue = $this->Residues->get($id);

        $assets = TableRegistry::get('Assets');

        //Obtengo los activos que estan en el acta de residuos
        $query2 = $assets->find()
                        ->select(['assets.plaque'])
                        ->where(['assets.residues_id' => $id])
                        ->toList();

        //lo paso a objeto para manejarlo en vista
        $size = count($query2);

        $result2 = array_fill(0, $size, NULL);
        
        for($i = 0; $i < $size; $i++)
        {
            $result2[$i] =(object)$query2[$i]->assets;
        }


        if ($this->request->is(['patch', 'post', 'put'])) { 

            //saco la lista de placas señaladas y luego las paso a Array
            $check = $this->request->getData("checkList");
            $checksViejos = explode(",", $check);

            $residue = $this->Residues->patchEntity($residue, $this->request->getData());

            // le doy formato a la fecha para que mysql pueda guardarla correctamente
            //$date = new Date($residue->date);
            //$residue->date= $date->format('Y-m-d');
            if ($this->Residues->save($residue)) {
                AppController::insertLog($residue['residues_id'], TRUE);
                $this->Flash->success(__('El acta de residuo ha sido guardada'));

                //Sección para grid con checks
                //Se crea un temporal con los activos en el acta
                $tmp = array_fill(0, $size, NULL);

                $i = 0;

                foreach ($result2 as $res) {

                    $tmp[$i] = $res -> plaque;
                    $i++;
                }

                //Se crea arreglo con nuevos activos en el acta
                $nuevos = array_diff($checksViejos, $tmp);
                //Se crea arreglo con activos que ya estaban en el acta
                $viejos = array_diff($tmp, $checksViejos);
                
                if (count($viejos) > 0) {

                        $assets = TableRegistry::get('Assets')->find('all');

                        //Se modifican los campos en activos para asegurar consistencia
                        $assets->update()
                                ->set(['residues_id' => NULL, 'state' => "Disponible"])
                                ->where(['plaque IN' => $viejos])
                                ->execute();

                        $technical_reports = TableRegistry::get('TechnicalReports')->find('all');

                        //Se modifican los campos en informe técnico para asegurar consistencia
                        $technical_reports->update()
                                            ->set(['residues_id' => NULL])
                                            ->where(['assets_id IN' => $viejos])
                                            ->execute();
                }

                 if (count($nuevos) > 0) {

                         $assets = TableRegistry::get('Assets')->find('all');
                        
                        //Se modifican los campos en activos para asegurar consistencia
                         $assets->update()
                                ->set(['residues_id' => $residue->residues_id, 'state' => "Desechado"])
                                ->where(['plaque IN' => $nuevos])
                                ->execute();

                         $technical_reports = TableRegistry::get('TechnicalReports')->find('all');

                         //Se modifican los campos en informe técnico para asegurar consistencia
                         $technical_reports->update()
                                             ->set(['residues_id' => $residue->residues_id])
                                             ->where(['assets_id IN' => $nuevos])
                                             ->execute();
                 }

                return $this->redirect(['action' => 'index']);
            }

            AppController::insertLog($residue['residues_id'], FALSE);
            
            $this->Flash->error(__('El acta de residuo no se ha guardado, inténtelo de nuevo'));

        }

        // aqui pasa a sacar los valores de result2 e indexarlos
        $lastPlaques =array_column($result2, 'plaque');
        /** se obtienen los datos de los activos que se quieren desechar*/
        $technical_reports = TableRegistry::get('TechnicalReports');
        $query = $technical_reports->find()
                        ->select(['assets.plaque', 'brands.name', 'models.name', 'assets.series', 'assets.state'])
                        ->join ([
                            'assets'=> [
                                'table'=>'assets',
                                'type'=>'INNER',
                                'conditions'=> ['assets.plaque= TechnicalReports.assets_id']
                            ]
                        ])
                        ->join([
                            'models' => [
                                    'table' => 'models',
                                    'type'  => 'LEFT',
                                    'conditions' => ['assets.models_id= models.id']
                                ]
                                ])
                         ->join([
                            'brands' => [
                                    'table' => 'brands',
                                    'type'  => 'LEFT',
                                    'conditions' => ['models.id_brand = brands.id']
                                ]
                                ])
                        ->where(['OR'=>[
                                        ['AND'=>[
                                                 ['TechnicalReports.recommendation' => "D"],
                                                 ['assets.state' => 'Disponible']
                                                ]
                                        ],
                                        ['assets.plaque in'=>array_column($result2, 'plaque')]
                                       ]
                                ])
                        //->where(['assets.state not like' => 'Des%'])
                        //->where(['TechnicalReports.recommendation' => "D"])
                        //->where(['or assets.plaque in'=>$result2 ])
                        ->group(['assets.plaque'])
                        ->toList();
        //debug($query);
        $size = count($query);
        $result = array_fill(0, $size, NULL);
        
        for($i = 0; $i < $size; $i++)
        {
            //* se acomodan los valores dentro de un mismo [$i]
            $result[$i]['plaque']= $query[$i]->assets['plaque'];
            $result[$i]['brand']= $query[$i]->brands['name'];
            $result[$i]['model']= $query[$i]->models['name'];
            $result[$i]['series']= $query[$i]->assets['series'];
            $result[$i]['state']= $query[$i]->assets['state'];
            // se realiza una conversion a objeto para que la vista lo use sin problemas
            $result[$i]= (object)$result[$i];
        }

        

        $Unidad = $this->UnidadAcadémica;
        //debug($residue);
        $this->set(compact('residue', 'result', 'result2', 'Unidad'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Residue id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    //borra la referencia en assets(activos) y borra el acta de desecho con el id enviado
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $assets = TableRegistry::get('Assets')->find()->where(['residues_id' => $id]);
        //se actualiza el estado del activo en la tabla de activos
        $assets->update()
        ->set(['residues_id' => null, 'state' => "Disponible"])
        ->where(['residues_id' => $id])
        ->execute();
        $residue = $this->Residues->get($id);
        //se quita la llave foránea para poder borrar el activo.
        $technical_reports = TableRegistry::get('TechnicalReports')->find('all');

                         $technical_reports->update()
                                             ->set(['residues_id' => null])
                                             ->where(['residues_id' => $residue->residues_id])
                                             ->execute();
        if ($this->Residues->delete($residue)) {
            AppController::insertLog($residue['residues_id'], TRUE);
            $this->Flash->success(__('El acta de residuo ha sido eliminada.'));
        } else {
            AppController::insertLog($residue['residues_id'], FALSE);
            $this->Flash->error(__('El acta de residuo no puede ser eliminada, inténtalo de nuevo'));
        }

        return $this->redirect(['action' => 'index']);
    }


    /** método para la ventana intermedia para insertar un acta de desechos */
    public function viewDownload($id = null)
    {
        $residue = $this->Residues->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) { 

            $residue = $this->Residues->patchEntity($residue, $this->request->getData());

            // le doy formato a la fecha para que mysql pueda guardarla correctamente
            $date = new Date($residue->date);
            $residue->date= $date->format('Y-m-d');
            if ($this->Residues->save($residue)) {
                AppController::insertLog($residue['residues_id'], TRUE);
                $this->Flash->success(__('El acta de residuo ha sido guardada'));
                //debug($residue);
                return $this->redirect(['action' => 'index']);
            }

            AppController::insertLog($residue['residues_id'], FALSE);
            
            $this->Flash->error(__('El acta de residuo no se ha guardado, inténtelo de nuevo'));

        }
        //obtengo la tabla assets
        $assets = TableRegistry::get('Assets');
        //busco los datos que necesito
        $assetsquery = $assets->find()
                        ->select(['assets.plaque'])
                        ->where(['residues_id'=>$id])
                        ->toList();

        //lo paso a objeto para manejarlo en vista
        $size = count($assetsquery);
        $result = array_fill(0, $size, NULL);
        
        for($i = 0; $i < $size; $i++)
        {
            $result[$i] =(object)$assetsquery[$i]->assets;
        }

        //obtengo la tabla technical_reports
        $technical_reports = TableRegistry::get('TechnicalReports');
        //busco los datos que necesito
        $queryRec = $technical_reports->find()
                                    ->select(['recommendation', 'technical_report_id'])
                                    ->where(['residues_id'=>$id])
                                    ->group(['assets_id'])
                                    ->toList();

        //lo paso a objeto para manejarlo en vista
        $size = count($queryRec);

        $resultRec = array_fill(0, $size, NULL);
        for($i = 0; $i < $size; $i++)
        {
            // se realiza una conversion a objeto para que la vista lo use sin problemas
            $resultRec[$i]= (object)$queryRec[$i];
        }

        $Unidad = $this->UnidadAcadémica;
        $this->set(compact('residue', 'result', 'resultRec', 'Unidad'));
    }



    public function download($id = null)
    {
        /*$conn = ConnectionManager::get('default');
        $stmt = $conn->execute('SELECT * FROM assets
            inner join residues on assets.residues_id = residues.residues_id
            where residues.residues_id =\'' . $id . '\';');
        $results = $stmt ->fetchAll('assoc');
         require_once 'dompdf/autoload.inc.php';

        //initialize dompdf class*/

        // se crea una entidad para luego poder hacer los validadores
        $residue = $this->Residues->newEntity();
        // Esta variable es simplemente para contener los datos en una estructura de array
        //que entienda el patchEntity
        $residueTMP;

        // Aqui queda el resultado, en un vector genérico, de lo que contiene la vista
        $residueArray= explode(',',$this->request->data('pdf') );

        //re realiza una relacion 1 a 1
        $residueTMP['residues_id']= $residueArray[0];
        $date = new Date($residueArray[1]);
        $residueTMP['date']= $residueArray[1];


        $residueTMP['name1']= $residueArray[2];
        $residueTMP['identification1']= $residueArray[3];
        $residueTMP['name2']= $residueArray[4];
        $residueTMP['identification2']= $residueArray[5];

        $residue = $this->Residues->patchEntity($residue,$residueTMP);
        $errors = $residue->errors();

        $residueTMP['date']= $date->format('Y-m-d');
        // linea para marcar el desecho como descargado, haciendo que ya no se pueda borrar
        $residue->descargado = true;
        // Actualizo el desecho, guardando el valor de descargado como true
        //y de paso se validan los campos para mayor seguridad del PDF

        if($errors== null && $this->Residues->save($residue))
        {
            // pide la lista de placas a la vista
            $plaques= explode(',',$this->request->data('plaques') );

            //  las placas se pasan a un formato de string de manera que seaan válidas en
            //el where assets.plaque in
            $plaqueList;
            $plaqueList.="'".$plaques[0]."'";
            $size=count($plaques);
            for($p=1;$p< $size;$p++)
            {
                $plaqueList.=",'".$plaques[$p]."'";
            }
            
            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute("SELECT description, plaque FROM assets
            where assets.plaque in (". $plaqueList .");");
            $results = $stmt ->fetchAll('assoc');

            require_once 'dompdf/autoload.inc.php';

            $document = new Dompdf();
            $html = 
            '
            <style>
            #element1 {float:left;margin-right:10px;} #element2 {float:right;} 
            table, td, th {
            border: 1px solid black;
            }
            body {
                border: 5px double;
                width:100%;
                height:100%;
                display:block;
                overflow:hidden;
                padding:30px 30px 30px 30px
            }
            table {
                border-collapse: collapse;
                border: none;
                width: 100%;
            }
            th {
                height: 50px;
            }
            </style>
            <center><img src="C:\xampp\htdocs\Decanatura\src\Controller\images\logoucr.png"></center>
            <title>Informe Técnico</title>
            <h2 align="center">UNIVERSIDAD DE COSTA RICA</h2>
            <h2 align="center">UNIDAD DE ACTIVOS FIJOS</h2>
            <h2 align="center">ACTA DE DESECHO</h2>
            <p>&nbsp;</p>
            <div id="element2" align="left"><strong>Autorización N.º VRA-'.$residue->residues_id.'</strong></div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Unidad de Custodio:'.$this->UnidadAcadémica.'</strong></div>
            <p>&nbsp;</p>
            <p align="left">El dia <strong>'.$residue->date.'</strong> en presencia de los señores:</p>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Nombre:</strong>'.$residue->name1.'</div> <div id="element2" align="right"><strong>Cedula:</strong>'.$residue->identification1.'</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Nombre:</strong>'.$residue->name2.'</div> <div id="element2" align="right"><strong>Cedula:</strong>'.$residue->identification2.'</div>
            <p>&nbsp;</p>
            <p>Se procede a levantar el <strong>Acta de Desecho</strong> de bienes muebles por haber cumplido su periodo de vida útil, de acuerdo con el <strong>Informe Técnico</strong> adjunto y la respectiva autorización por parte de la Vicerrectoría de Administración, de conformidad con el Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica</p>
            <p align="left">Los bienes son los siguientes:</p>
            <p>&nbsp;</p>
            </table>
            <table width="0" border="1">
            <tbody>
            <tr>
            <th align="center"><strong>DESCRIPCIÓN DEL BIEN</strong></th>
            <th align="center"><strong>N.º PLACA</strong></th>
            </tr>';
            for($a=0;$a < $size; $a++) {
            $html .= 
            '<tr>
                <td align="center">' . $results[$a]['description']. '</td>
                <td align="center">' . $results[$a]['plaque'] . '</td>
             </tr>';

            }
            $html .=
            '</table>
            <p>&nbsp;</p>
            <div id="element1" align="left">____________________________________________</div> <div id="element2" align="right">____________________________________________</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Autoridad Universitaria / Jefatura Administrativa</strong></div> <div id="element2" align="right"><strong>RESPONSABLE AUTORIZADO</strong></div>        
            <p>&nbsp;</p>
            <div id="element2" align="left"><strong>Oficina de Servicios Generales<strong></div>
            <p>&nbsp;</p>
            <div id="element1" align="left">____________________________________________</div> <div id="element2" align="right">____________________________________________</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Testigo N°1</strong></div> <div id="element2" align="right"><strong>Testigo N°2</strong></div>
            <p>&nbsp;</p>
            <p>(Art. 26 del Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica)</p>
            <p>Original: Unidad de Bienes Institucionales (OAF)</p>
            <p>Copia: Bodega de Activos Recuperados (OSG)</p>
            <p>Copia: Unidad responsable</p>        
            <p>&nbsp;</p>
            <p align="center">Tels: 2511 5759/1149      www.oaf.ucr.ac.cr     correo electrónico: activosfijos.oaf@ucr.ac.cr</p>
            ';
            
            $document->loadHtml($html);
            //set page size and orientation
            $document->setPaper('A3', 'portrait');
            //Render the HTML as PDF
            $document->render();
            //Get output of generated pdf in Browser
            $document->stream("Acta de Desecho-".$residue->residues_id, array("Attachment"=>1));
            //1  = Download
            //0 = Preview
        }
        $this->Flash->error(__('El acta de desechos no se ha generado. Existe un error en los campos editables.'));
        return $this->redirect(['action' => 'edit',$residue->residues_id]);
    }



        public function generate($id = null)
    {
        

        $residue = $this->Residues->get($id);



            // linea para marcar el desecho como descargado, haciendo que ya no se pueda borrar
            $residue->descargado = true;
            // Actualizo el desecho, guardando el valor de descargado como true
             //y de paso se validan los campos para mayor seguridad del PDF
            $this->Residues->save($residue);

            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute("SELECT description, plaque FROM assets
            where assets.residues_id = '". $id ."';");
            $results = $stmt ->fetchAll('assoc');
            $size = count($results);
            require_once 'dompdf/autoload.inc.php';

            $document = new Dompdf();
            $html = 
            '
            <style>
            #element1 {float:left;margin-right:10px;} #element2 {float:right;} 
            table, td, th {
            border: 1px solid black;
            }
            body {
                border: 5px double;
                width:100%;
                height:100%;
                display:block;
                overflow:hidden;
                padding:30px 30px 30px 30px
            }
            table {
                border-collapse: collapse;
                border: none;
                width: 100%;
            }
            th {
                height: 50px;
            }
            </style>
            <center><img src="C:\xampp\htdocs\Decanatura\src\Controller\images\logoucr.png"></center>
            <title>Informe Técnico</title>
            <h2 align="center">UNIVERSIDAD DE COSTA RICA</h2>
            <h2 align="center">UNIDAD DE ACTIVOS FIJOS</h2>
            <h2 align="center">ACTA DE DESECHO</h2>
            <p>&nbsp;</p>
            <div id="element2" align="left"><strong>Autorización N.º VRA-'.$residue->residues_id.'</strong></div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Unidad de Custodio:'.$this->UnidadAcadémica.'</strong></div>
            <p>&nbsp;</p>
            <p align="left">El dia <strong>'.$residue->date.'</strong> en presencia de los señores:</p>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Nombre:</strong>'.$residue->name1.'</div> <div id="element2" align="right"><strong>Cedula:</strong>'.$residue->identification1.'</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Nombre:</strong>'.$residue->name2.'</div> <div id="element2" align="right"><strong>Cedula:</strong>'.$residue->identification2.'</div>
            <p>&nbsp;</p>
            <p>Se procede a levantar el <strong>Acta de Desecho</strong> de bienes muebles por haber cumplido su periodo de vida útil, de acuerdo con el <strong>Informe Técnico</strong> adjunto y la respectiva autorización por parte de la Vicerrectoría de Administración, de conformidad con el Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica</p>
            <p align="left">Los bienes son los siguientes:</p>
            <p>&nbsp;</p>
            </table>
            <table width="0" border="1">
            <tbody>
            <tr>
            <th align="center"><strong>DESCRIPCIÓN DEL BIEN</strong></th>
            <th align="center"><strong>N.º PLACA</strong></th>
            </tr>';
            for($a=0;$a < $size; $a++) {
            $html .= 
            '<tr>
                <td align="center">' . $results[$a]['description']. '</td>
                <td align="center">' . $results[$a]['plaque'] . '</td>
             </tr>';

            }
            $html .=
            '</table>
            <p>&nbsp;</p>
            <div id="element1" align="left">____________________________________________</div> <div id="element2" align="right">____________________________________________</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Autoridad Universitaria / Jefatura Administrativa</strong></div> <div id="element2" align="right"><strong>RESPONSABLE AUTORIZADO</strong></div>        
            <p>&nbsp;</p>
            <div id="element2" align="left"><strong>Oficina de Servicios Generales<strong></div>
            <p>&nbsp;</p>
            <div id="element1" align="left">____________________________________________</div> <div id="element2" align="right">____________________________________________</div>
            <p>&nbsp;</p>
            <div id="element1" align="left"><strong>Testigo N°1</strong></div> <div id="element2" align="right"><strong>Testigo N°2</strong></div>
            <p>&nbsp;</p>
            <p>(Art. 26 del Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica)</p>
            <p>Original: Unidad de Bienes Institucionales (OAF)</p>
            <p>Copia: Bodega de Activos Recuperados (OSG)</p>
            <p>Copia: Unidad responsable</p>        
            <p>&nbsp;</p>
            <p align="center">Tels: 2511 5759/1149      www.oaf.ucr.ac.cr     correo electrónico: activosfijos.oaf@ucr.ac.cr</p>
            ';
            
            $document->loadHtml($html);
            //set page size and orientation
            $document->setPaper('A3', 'portrait');
            //Render the HTML as PDF
            $document->render();
            //Get output of generated pdf in Browser
            $document->stream("Acta de Desecho-".$residue->residues_id, array("Attachment"=>1));
            //1  = Download
            //0 = Preview
        $this->Flash->error(__('El acta de desechos no se ha generado.'));
    }

     public function download2($id = null)
    {
        $residue = $this->Residues->get($id);
        $path=WWW_ROOT.'files'.DS.'Residues'.DS.'file'.DS.$residue->residues_id.DS.$residue->file;
        $this->response->file($path, array(
        'download' => true,
        'name' =>$residue->file ,
        ));
        return $this->response;
        /*$this->Flash->error($path);
        return $this->redirect(['action' => 'edit',$residue->residues_id]);*/
    }
}