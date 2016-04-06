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
require_model('divisa.php');
require_model('tipocambio.php');
require_model('valorestipocambio.php');
/**
 * Description of contabilidad_tipocambio
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class contabilidad_tipocambio extends fs_controller {
    public $divisa;
    public $tipocambio;
    public $valorestipocambio;
    public $listado;

    public function __construct() {
        parent::__construct(__CLASS__, 'Tipo de Cambio', 'contabilidad', FALSE, TRUE, TRUE);
    }

    protected function private_core() {
        $this->divisa = new divisa();
        $this->tipocambio = new tipocambio();
        $this->valorestipocambio = new valorestipocambio();

        $type = filter_input(INPUT_POST, 'type');
        $delete = filter_input(INPUT_GET, 'delete');
        if(isset($type) AND $type == 'nuevo'){
            $idtipocambio = filter_input(INPUT_POST, 'idtipocambio');
            $fecha = filter_input(INPUT_POST, 'fecha');
            $coddivisa = filter_input(INPUT_POST, 'coddivisa');
            $factor = filter_input(INPUT_POST, 'factor');
            $vtc = new valorestipocambio();
            $vtc->idtipocambio = $idtipocambio;
            $vtc->coddivisa = $coddivisa;
            $vtc->fecha = $fecha;
            $vtc->factor = $factor;
            if(isset($delete)){
                if($vtc->delete()){
                    $this->new_message("¡Tipo de cambio eliminado correctamente!");
                }
            }else{
                if($vtc->save()){
                    $this->new_message("¡Tipo de cambio guardado correctamente!");
                }
            }
        }
        $datos_almacenados = $this->valorestipocambio->all();
        $this->listado_tiposcambio($datos_almacenados);
    }

    public function listado_tiposcambio($datos_almacenados){
        $listado = array();
        if(!empty($datos_almacenados)){
            foreach($datos_almacenados as $valores){
                $listado[$valores->idtipocambio][] = $valores;
            }
        }
        $this->listado = $listado;
    }
}
