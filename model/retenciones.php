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
class retenciones extends fs_model{
    public $id;
    public $codejercicio;
    public $tipo;
    public $descripcion;
    public $porcentaje;
    public $estado;
    public $fecha_creacion;
    public $usuario_creacion;
    public $fecha_modificacion;
    public $usuario_modificacion;

    public function __construct($t = FALSE) {
        parent::__construct('co_retenciones','plugins/contabilidad_avanzada/');
        if($t){
            $this->id = $t['id'];
            $this->codejercicio = $t['codejercicio'];
            $this->tipo = $t['tipo'];
            $this->descripcion = $t['descripcion'];
            $this->porcentaje = $t['porcentaje'];
            $this->estado = $this->str2bool($t['estado']);
            $this->fecha_creacion = $t['fecha_creacion'];
            $this->usuario_creacion = $t['usuario_creacion'];
            $this->fecha_modificacion = $t['fecha_modificacion'];
            $this->usuario_modificacion = $t['usuario_modificacion'];
        }else{
            $this->id = null;
            $this->codejercicio = null;
            $this->tipo = null;
            $this->descripcion = null;
            $this->porcentaje = 0;
            $this->estado = false;
            $this->fecha_creacion = null;
            $this->usuario_creacion = null;
            $this->fecha_modificacion = null;
            $this->usuario_modificacion = null;
        }
    }

    public function url() {
        if(is_null($this->id)){
            return 'index.php?page=maestro_retenciones';
        }else{
            return 'index.php?page=contabilidad_retenciones&retencion='.$this->id;
        }
    }

    public function install() {
        return '';
    }

    public function exists() {
        if(is_null($this->id)){
            return false;
        }else{
            $sql = "SELECT * from ".$this->table_name." where".
                    " id = ".$this->intval($this->id).";";
            $data = $this->db->select($sql);
            if($data){
                return true;
            }else{
                return false;
            }
        }
    }

    public function save() {
        $valor = false;
        if($this->exists()){
            $this->update();
        }else{
            $sql = "INSERT INTO ".$this->table_name." (codejercicio, tipo, descripcion, porcentaje, estado, fecha_creacion, usuario_creacion) VALUES (".
                $this->var2str($this->codejercicio).", ".
                $this->var2str($this->tipo).", ".
                $this->var2str($this->descripcion).", ".
                $this->var2str($this->porcentaje).", ".
                $this->var2str($this->estado).", ".
                $this->var2str($this->fecha_creacion).", ".
                $this->var2str($this->usuario_creacion).");";
            if($this->db->exec($sql)){
                $valor = true;
            }else{
                $this->new_error_msg("Ocurrio un error al guardar la información de la retencion");
                $valor = false;
            }
            return $valor;
        }
    }

    public function update() {
        $sql = "UPDATE ".$this->table_name." SET ".
                "estado = ".$this->var2str($this->estado).", ".
                "tipo = ".$this->var2str($this->tipo)." ".
                "porcentaje = ".$this->var2str($this->porcentaje)." ".
                "fecha_modificacion = ".$this->var2str($this->fecha_modificacion)." ".
                "usuario_modificacion = ".$this->var2str($this->usuario_modificacion)." ".
                "WHERE ".
                "id = ".$this->intval($this->id)." AND ".
                "tipo = ".$this->var2str($this->tipo).";";
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
                "id = ".$this->intval($this->id)." AND ".
                "tipo = ".$this->var2str($this->tipo).";";
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
        $sql = "SELECT * FROM ".$this->table_name." order by codejercicio DESC, tipo ASC";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new retenciones($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function all_activos($offset = 0){
        $sql = "SELECT * FROM ".$this->table_name." WHERE estado = TRUE order by codejercicio DESC, tipo ASC";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new retenciones($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function all_tipo($tipo, $offset = 0){
        $sql = "SELECT * FROM ".$this->table_name." WHERE tipo=".$this->var2str($tipo)." order by codejercicio DESC, tipo ASC";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new retenciones($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function all_tipo_activos($tipo, $offset = 0){
        $sql = "SELECT * FROM ".$this->table_name." WHERE tipo=".$this->var2str($tipo)." AND estado = true order by codejercicio DESC, tipo ASC";
        $data = $this->db->select_limit($sql,FS_ITEM_LIMIT,$offset);
        if($data){
            $lista = array();
            foreach($data as $linea){
                $lista[] = new retenciones($linea);
            }
            return $lista;
        }else{
            return false;
        }
    }

    public function get($id){
        $sql = "SELECT * FROM ".$this->table_name." WHERE ".
                "id = ".$this->intval($id).";";
        $data = $this->db->select($sql);
        if($data){
            return new retenciones($data[0]);
        }else{
            return false;
        }

    }

}
