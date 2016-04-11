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
require_model('tasasconversion.php');
/**
 * Description of contabilidad_tipocambio
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class contabilidad_tasasconversion extends fs_controller {
    public $divisaempresa;
    public $divisadestino;
    public $tasasconversion;
    public $listado;
    public $totallistado;
    public function __construct() {
        parent::__construct(__CLASS__, 'Historial de Tasas', 'contabilidad', FALSE, FALSE, FALSE);
    }

    protected function private_core() {
        $this->divisa = new divisa();
        $this->tasasconversion = new tasasconversion();
        $this->totallistado = 0;
        $divisaemp = filter_input(INPUT_GET, 'divisaemp');
        $coddivisa = filter_input(INPUT_GET, 'coddivisa');
        if(isset($divisaemp) AND isset($coddivisa)){
            $this->listado = $this->tasasconversion->historial($divisaemp,$coddivisa);
            //$this->listado_tasasconversion($historial);
            $this->divisaempresa = $divisaemp;
            $this->divisadestino = $coddivisa;
        }

    }

    public function listado_tasasconversion($datos_almacenados){
        $listado = array();
        if(!empty($datos_almacenados)){
            foreach($datos_almacenados as $valores){
                $listado[$valores->tipo][] = $valores;
            }
        }
        $this->listado = $listado;
    }
}
