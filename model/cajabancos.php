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
 * Description of cajabancos
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class cajabancos extends fs_model {

    public function __construct($t = FALSE) {
        parent::__construct('co_cajabancos','plugins/contabilidad_avanzada/');
        if($t){

        }else{

        }
    }

    protected function install() {
        return '';
    }

    public function exists() {
        return '';
    }

    public function save() {
        return '';
    }

    public function delete() {
        return '';
    }
}
