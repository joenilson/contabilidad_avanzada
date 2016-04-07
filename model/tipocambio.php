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

/**
 * Description of tiposcambio
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class tipocambio extends fs_model {
    public $id;
    public $descripcion;
    public $diaria;
    public $estado;
    public function __construct($t = FALSE) {
        parent::__construct('co_tipocambio','plugins/contabilidad_avanzada/');
        if($t){
            $this->id = $t['id'];
            $this->descripcion = $t['descripcion'];
            $this->diaria = $this->str2bool($t['diaria']);
            $this->estado = $this->str2bool($t['estado']);
        }else{
            $this->id = null;
            $this->descripcion = null;
            $this->diaria = FALSE;
            $this->estado = FALSE;
        }
    }

    public function url()
    {
        if( is_null($this->id) )
        {
            return 'index.php?page=maestro_tipocambio';
        }
        else
        {
            return 'index.php?page=tipocambio='.$this->id;
        }
   }

    protected function install() {
        return "INSERT INTO co_tipocambio (descripcion, diaria, estado) VALUES ('Venta',FALSE,TRUE), ('Compra',FALSE,TRUE), ('Presupuesto',FALSE,TRUE);";
    }

    public function exists() {
        if(is_null($this->id)){
            return false;
        }else{
            $sql = "SELECT * FROM ".$this->table_name." where id = ".$this->intval($this->id).";";
            $data = $this->db->select($sql);
            if($data){
                return true;
            }else{
                return false;
            }
        }
    }

    public function save() {
        if($this->exists()){
            $sql = "UPDATE ".$this->table_name.
                    " SET descripcion = ".$this->var2str($this->descripcion)
                    .", diaria = ".$this->var2str($this->diaria)
                    .", estado = ".$this->var2str($this->estado)
                    . " WHERE id = ".$this->intval($this->id).";";
            try {
                $this->db->exec($sql);
                return true;
            } catch (Exception $ex) {
                $this->new_error_msg("Error al actualizar la información del tipo de cambio: ".$ex);
            }
        }else{
            $sql = "INSERT INTO ".$this->table_name." (descripcion, diaria, estado) VALUES (".
                    $this->var2str($this->descripcion).", ".
                    $this->var2str($this->diaria).", ".
                    $this->var2str($this->estado).");";
            try {
                $this->db->exec($sql);
                $valor = true;
            } catch (Exception $ex) {
                $this->new_error_msg("Error al guardar la información del tipo de cambio: ".$ex);
                $valor = false;
            }
            return $valor;
        }
    }

    public function delete() {
        $sql = "DELETE FROM ".$this->table_name
              ." WHERE id = ".$this->intval($this->id).";";
        try {
            $this->db->exec($sql);
            $valor = true;
        } catch (Exception $ex) {
            $this->new_error_msg("Error al eliminar la información del tipo de cambio: ".$ex);
            $valor = false;
        }
        return $valor;
    }

    public function all() {
        $sql = "SELECT * FROM ".$this->table_name." order by id";
        $data = $this->db->select($sql);
        if($data){
            $lista = array();
            foreach($data as $d){
                $linea = new tipocambio($d);
                $lista[]=$linea;
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function activos() {
        $sql = "SELECT * FROM ".$this->table_name." WHERE estado = TRUE order by id";
        $data = $this->db->select($sql);
        if($data){
            $lista = array();
            foreach($data as $d){
                $linea = new tipocambio($d);
                $lista[]=$linea;
            }
            return $lista;
        }else{
            return false;
        }
    }
}
