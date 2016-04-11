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
class tasasconversion extends fs_model{
    public $divisaemp;
    public $tipo;
    public $coddivisa;
    public $fecha;
    public $factor;

    public function __construct($t = FALSE) {
        parent::__construct('co_tasasconversion','plugins/contabilidad_avanzada/');
        if($t){
            $this->divisaemp = $t['divisaemp'];
            $this->tipo = $t['tipo'];
            $this->coddivisa = $t['coddivisa'];
            $this->fecha = $t['fecha'];
            $this->factor = floatval($t['factor']);
        }else{
            $this->divisaemp = null;
            $this->tipo = null;
            $this->coddivisa = null;
            $this->fecha = date('d-m-Y');
            $this->factor = 0;
        }
    }

    public function url() {
        return 'index.php?page=contabilidad_tasasconversion';
    }

    public function install() {
        return '';
    }

    public function exists() {
        $sql = "SELECT * from ".$this->table_name." where".
                " divisaemp = ".$this->var2str($this->divisaemp).
                " AND tipo = ".$this->var2str($this->tipo).
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
        $valor = false;
        if($this->exists()){
            $this->update();
        }else{
            $sql = "INSERT INTO ".$this->table_name." (divisaemp, tipo, coddivisa, fecha, factor) VALUES (".
                $this->var2str($this->divisaemp).", ".
                $this->var2str($this->tipo).", ".
                $this->var2str($this->coddivisa).", ".
                $this->var2str($this->fecha).", ".
                $this->var2str($this->factor).");";
            if($this->db->exec($sql)){
                $valor = true;
            }else{
                $this->new_error_msg("Ocurrio un error al guardar la información de la tasa de conversion");
                $valor = false;
            }
            return $valor;
        }
    }

    public function update() {
        $sql = "UPDATE ".$this->table_name." SET ".
                "fecha = ".$this->var2str($this->fecha).", ".
                "factor = ".$this->var2str($this->factor)." ".
                "WHERE ".
                "divisaemp = ".$this->var2str($this->divisaemp)." AND ".
                "tipo = ".$this->var2str($this->tipo)." AND ".
                "fecha = ".$this->var2str($this->fecha)." AND ".
                "coddivisa = ".$this->var2str($this->coddivisa).";";
        try {
            $this->db->exec($sql);
            $valor = true;
        } catch (Exception $ex) {
            $this->new_error_msg("Ocurrio el siguiente error al actualizar la información: ".$ex);
            $valor = false;
        }
        return $valor;
    }

    public function delete() {
        $sql = "DELETE FROM ".$this->table_name." WHERE ".
                "divisaemp = ".$this->var2str($this->divisaemp)." AND ".
                "tipo = ".$this->var2str($this->tipo)." AND ".
                "fecha = ".$this->var2str($this->fecha)." AND ".
                "coddivisa = ".$this->var2str($this->coddivisa).";";
        try{
            $this->db->exec($sql);
            $valor = true;
        } catch (Exception $ex) {
            $this->new_error_msg("Ocurrio el siguiente error al eliminar la información: ".$ex);
            $valor = false;
        }
        return $valor;
    }

    public function all($offset = 0){
        $sql = "SELECT * FROM ".$this->table_name." order by tipo, fecha, coddivisa ";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new tasasconversion($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function historial($divisaemp, $coddivisa, $offset = 0){
        $sql = "SELECT * FROM ".$this->table_name.
                " WHERE divisaemp = ".$this->var2str($divisaemp).
                " AND coddivisa = ".$this->var2str($coddivisa).
                " order by tipo, fecha, coddivisa ";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new tasasconversion($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function get($divisaemp, $tipo, $fecha, $coddivisa){
        $sql = "SELECT * FROM ".$this->table_name." WHERE ".
                "divisaemp = ".$this->var2str($divisaemp)." AND ".
                "tipo = ".$this->var2str($tipo)." AND ".
                "fecha = ".$this->var2str($fecha)." AND ".
                "coddivisa = ".$this->var2str($coddivisa).";";
        $data = $this->db->select($sql);
        if($data){
            return new tasasconversion($data[0]);
        }else{
            return false;
        }

    }

    public function ultima_tasa($divisaemp, $tipo, $coddivisa){
        $sql = "SELECT * FROM ".$this->table_name." WHERE ".
                "divisaemp = ".$this->var2str($divisaemp)." AND ".
                "tipo = ".$this->var2str($tipo)." AND ".
                "coddivisa = ".$this->var2str($coddivisa)." ORDER BY fecha DESC LIMIT 1;";
        $data = $this->db->select($sql);
        //var_dump($data);
        if(($data)){
            return new tasasconversion($data[0]);
        }else{
            return false;
        }
    }
}
