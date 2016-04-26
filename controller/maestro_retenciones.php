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
    public $ejercicios;
    public $retenciones;
    public function __construct() {
        parent::__construct(__CLASS__, 'Retenciones', 'contabilidad', FALSE, TRUE, FALSE);
    }

    protected function private_core() {
        $this->ejercicios = new ejercicio();
        $this->retenciones = new retenciones();

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

    }

    public function eliminar_retenciones(){

    }
}
