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
require_model('tipocambio.php');
/**
 * Description of maestro_tipocambio
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class maestro_tipocambio extends fs_controller{
    public $tipocambio;
    public function __construct() {
        parent::__construct(__CLASS__, 'Maestro Tipo de Cambio', 'contabilidad', FALSE, TRUE, FALSE);
    }

    protected function private_core() {
        $this->tipocambio = new tipocambio();
        $delete = filter_input(INPUT_GET, 'delete');
        $type = filter_input(INPUT_GET, 'type');

        $id = filter_input(INPUT_POST, 'id');
        $descripcion = filter_input(INPUT_POST, 'descripcion');
        if((isset($descripcion) AND !empty($descripcion)) OR (isset($delete))){
            $val_diaria = filter_input(INPUT_POST, 'diaria');
            $val_estado = filter_input(INPUT_POST, 'estado');
            $diaria = (isset($val_diaria))?TRUE:FALSE;
            $estado = (isset($val_estado))?TRUE:FALSE;
            $tc0 = new tipocambio();
            $tc0->id = $id;
            $tc0->descripcion = $this->clean_string($descripcion);
            $tc0->diaria = $diaria;
            $tc0->estado = $estado;
            if($delete){
                $tc0->id = $delete;
                try{
                    $tc0->delete();
                    $this->new_message("Informacion del tipo de cambio eliminada correctamente.");
                } catch (Exception $ex) {
                    $this->new_error_msg("Ocurrio un error al grabar la informacion: ".$ex);
                }
            }else{
                try{
                    $tc0->save();
                    $this->new_message("Informacion del tipo de cambio grabada correctamente.");
                } catch (Exception $ex) {
                    $this->new_error_msg("Ocurrio un error al grabar la informacion: ".$ex);
                }
            }
        }
    }

    protected function clean_string($str){
        return stripslashes(stripcslashes(strip_tags(trim($str))));
    }
}
