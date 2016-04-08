<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2014-2016  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_model('divisa.php');
require_model('tasasconversion.php');

class admin_divisas extends fs_controller
{
   public $allow_delete;
   public $divisa;
   public $tasasconversion;
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Divisas', 'admin', TRUE, TRUE);
   }

   protected function private_core()
   {
      $this->divisa = new divisa();
      $this->tasasconversion = new tasasconversion();
      /// ¿El usuario tiene permiso para eliminar en esta página?
      $this->allow_delete = $this->user->allow_delete_on(__CLASS__);

      if( isset($_POST['coddivisa']) )
      {
         $div0 = $this->divisa->get($_POST['coddivisa']);
         if(!$div0)
         {
            $div0 = new divisa();
            $div0->coddivisa = $_POST['coddivisa'];
         }
         $div0->simbolo = $_POST['simbolo'];
         $div0->descripcion = $_POST['descripcion'];
         $div0->codiso = $_POST['codiso'];
         $div0->tasaconv = floatval(0);
         $div0->tasaconv_compra = floatval(0);
         if( $div0->save() )
         {
            $this->new_message('Divisa '.$div0->coddivisa.' guardada correctamente.');
         }
         else
            $this->new_error_msg('Error al guardar la divisa.');
      }
      else if( isset($_GET['delete']) )
      {
         $div0 = $this->divisa->get($_GET['delete']);
         if($div0)
         {
            if( !$this->user->admin )
            {
               $this->new_error_msg('Sólo un administrador puede eliminar divisas.');
            }
            else if( $div0->delete() )
            {
               $this->new_message('Divisa '.$div0->coddivisa.' eliminada correctamente.');
            }
            else
               $this->new_error_msg('Error al eliminar la divisa '.$div0->coddivisa.'.');
         }
         else
            $this->new_error_msg('Divisa no encontrada.');
      }
      elseif( isset($_POST['agregar_tasa']) ){
          $valor = false;
          $tc0 = new tasasconversion();
          $tc0->divisaemp = filter_input(INPUT_POST, 'divisaemp');
          $tc0->fecha = filter_input(INPUT_POST, 'fecha');
          $tc0->tipo = strtolower(filter_input(INPUT_POST, 'tipo_conversion'));
          $tc0->coddivisa = filter_input(INPUT_POST, 'divisa');
          $tc0->factor = floatval(filter_input(INPUT_POST, 'tasa_conversion'));
          if($tc0->save()){
                $valor = true;
          } else {
                $valor = false;
          }
          if($valor){
              $this->new_message('Tasa de conversion de '.ucfirst($tc0->tipo).' para moneda '.$tc0->coddivisa.' agregada correctamente.');
          }
      }
   }
}
