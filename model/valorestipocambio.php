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
 * Description of valorestipocambio
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class valorestipocambio extends fs_model{
    public $idtipocambio;
    public $coddivisa;
    public $fecha;
    public $factor;

    public function __construct($t = FALSE) {
        parent::__construct('co_valorestipocambio','plugins/contabilidad_avanzada/');
        if($t){
            $this->id = $t['idtipocambio'];
            $this->coddivisa = $t['coddivisa'];
            $this->fecha = $t['fecha'];
            $this->factor = floatval($t['factor']);
        }else{
            $this->idtipocambio = null;
            $this->coddivisa = null;
            $this->fecha = date('d-m-Y');
            $this->factor = 0;
        }
    }

    public function url() {
        return 'index.php?page=contabilidad_tipocambio';
    }

    public function install() {
        return '';
    }

    public function exists() {
        $sql = "SELECT * from ".$this->table_name." where".
                " idtipocambio = ".$this->intval($this->idtipocambio).
                " AND fecha = ".$this->var2str($this->fecha).
                " AND coddivisa = ".$this->var2str($this->coddivisa).";";
        $data = $this->db->select($sql);
        if($data){
            return true;
        }else{
            return false;
        }
    }

    public function save() {
        if($this->exists()){
            $this->update();
        }else{
            $sql = "INSERT INTO ".$this->table_name." (idtipocambio, coddivisa, fecha, factor) VALUES (".
                $this->intval($this->idtipocambio).", ".
                $this->var2str($this->coddivisa).", ".
                $this->var2str($this->fecha).", ".
                $this->var2str($this->factor).");";
            try {
                $this->db->exec($sql);
                return true;
            } catch (Exception $ex) {
                $this->new_error_msg("Ocurrio el siguiente error al guardar la información: ".$ex);
            }
        }
    }

    public function update() {
        $sql = "UPDATE ".$this->table_name." SET ".
                "fecha = ".$this->var2str($this->fecha).", ".
                "factor = ".$this->var2str($this->factor)." ".
                "WHERE ".
                "idtipocambio = ".$this->intval($this->idtipocambio)." AND ".
                "fecha = ".$this->var2str($this->fecha)." AND ".
                "coddivisa = ".$this->var2str($this->coddivisa).";";
        try {
            $this->db->exec($sql);
            return true;
        } catch (Exception $ex) {
            $this->new_error_msg("Ocurrio el siguiente error al actualizar la información: ".$ex);
        }
    }

    public function delete() {
        $sql = "DELETE FROM ".$this->table_name." WHERE ".
                "idtipocambio = ".$this->intval($this->idtipocambio)." AND ".
                "fecha = ".$this->var2str($this->fecha)." AND ".
                "coddivisa = ".$this->var2str($this->coddivisa).";";
        try{
            $this->db->exec($sql);
            return true;
        } catch (Exception $ex) {
            $this->new_error_msg("Ocurrio el siguiente error al eliminar la información: ".$ex);
        }
    }

    public function all($offset = 0){
        $sql = "SELECT * FROM ".$this->table_name." order by idtipocambio, fecha, coddivisa ";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new valorestipocambio($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function get($idtipocambio, $fecha, $coddivisa){
        $sql = "SELECT * FROM ".$this->table_name." WHERE ".
                "idtipocambio = ".$this->intval($idtipocambio)." AND ".
                "fecha = ".$this->var2str($fecha)." AND ".
                "coddivisa = ".$this->var2str($coddivisa).";";
        $data = $this->db->exec($sql);
        if($data){
            return new valorestipocambio($data[0]);
        }else{
            return false;
        }

    }

}
