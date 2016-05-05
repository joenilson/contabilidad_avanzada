<?php

/*
 * Copyright (C) 2016 Joe Nilson <joenilson at gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_model('ejercicio.php');
require_model('retenciones.php');
/**
 * Description of maestro_contabilidad
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class maestro_retenciones extends fs_controller{
    public $allow_delete;
    public $ejercicios;
    public $retencion;
    public $retenciones;
    public function __construct() {
        parent::__construct(__CLASS__, 'Retenciones', 'contabilidad', FALSE, TRUE, FALSE);
    }

    protected function private_core() {
        $this->ejercicios = new ejercicio();
        $this->retenciones = new retenciones();
        /// ¿El usuario tiene permiso para eliminar en esta página?
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
        if(isset($_REQUEST['proceso']) AND !empty($_REQUEST['proceso'])){
            $proceso = trim($_REQUEST['proceso']);
            if($proceso == 'guardar'){
                $this->guardar_retenciones();
            }elseif($proceso == 'eliminar'){
                $this->eliminar_retenciones();
            }
        }

        $this->resultados = $this->retenciones->all();
    }

    public function listar_retenciones(){

    }

    public function listar_grupoclientes(){

    }

    public function guardar_retenciones(){
        $codejercicio = \filter_input(INPUT_POST, 'ejercicio');
        $descripcion = \filter_input(INPUT_POST, 'descripcion');
        $tipo = \filter_input(INPUT_POST, 'tipo');
        $porcentaje = \filter_input(INPUT_POST, 'porcentaje');
        $r0 = new retenciones();
        $r0->codejercicio = $codejercicio;
        $r0->descripcion = stripcslashes(trim($descripcion));
        $r0->tipo = $tipo;
        $r0->porcentaje = floatval($porcentaje);
        $r0->usuario_creacion = $this->user->nick;
        $r0->fecha_creacion = date('Y-m-d h:i:s');
        $r0->estado = true;
        if($r0->save()){
            $this->new_message('Retención '.$r0->descripcion." guardada correctamente");
        }else{
            $this->new_error_msg('Ocurrió un error al guardar la retención, por favor verifique los datos e intente nuevamente.');
        }
    }

    public function eliminar_retenciones(){
       $id = \filter_input(INPUT_GET, 'id');
       $data = array();
       $data['success']=false;
       $data['mensaje']='No se pudo procesar la eliminación';
       $ret0 = $this->retenciones->get($id);
       if($ret0->delete()){
           $data['success']=true;
           $data['mensaje']='Retención eliminada';
       }
       if($data['success']){
           $this->new_message($data['mensaje']);
       }else{
           $this->new_error_msg($data['mensaje']);
       }
    }
}
