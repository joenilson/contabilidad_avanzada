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
require_model('articulo.php');
require_model('retenciones.php');
/**
 * Description of articulo_retenciones
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class articulo_retenciones extends fs_controller{
    public $articulo;
    public $referencia;
    public $articulos;
    public $retenciones;
    public function __construct() {
        parent::__construct(__CLASS__, 'Retenciones a Articulos', 'Contabilidad', TRUE, FALSE, FALSE);
    }

    protected function private_core() {
        $this->articulos = new articulo();
        $this->retenciones = new retenciones();
        $referencia_p = filter_input(INPUT_POST, 'ref');
        $referencia_g = filter_input(INPUT_GET, 'ref');
        $this->referencia = (!empty($referencia_p))?$referencia_p:$referencia_g;
        if(!empty($this->referencia)){
            $this->articulo = $this->articulos->get($this->referencia);
        }
    }
}
