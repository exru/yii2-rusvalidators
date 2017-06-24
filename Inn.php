<?php

/*
 * Copyright (C) 2017 exru.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace exru\rusvalidators;

use yii\validators\Validator;

class Inn extends Validator{
    static $FACTOR_10 = [2, 4, 10, 3, 5, 9, 4, 6, 8];
    static $FACTOR_11 = [7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
    static $FACTOR_12 = [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
    
    private function _getInnFactor($value, $factor) {
        $sum = 0;
        $number = count($factor);
        for ($i = 0; $i != $number; $i++) {
            $sum += $value[$i] * $factor[$i];
        }
        $sum -= intval($sum / 11) * 11;
        return $sum != 10 ? $sum : 0;
    }
    
    public function validateAttribute($model, $attribute)
    {
        $length = strlen($model->{$attribute});

        if ($length != 10 && $length != 12) {
            $model->addError($attribute, text('ИНН должен быть длинной 10 или 12 символов'));
            return;
        }

        if ($length == 10) {
            if (substr($model->{$attribute}, 9, 1) != $this->_getInnFactor($model->{$attribute}, self::$FACTOR_10)) {
                $model->addError($attribute, text('Неверный ИНН'));
                return;
            }
        }

        if ($length == 12) {
            if (substr($model->{$attribute}, 10, 1)  != $this->_getInnFactor($model->{$attribute}, self::$FACTOR_11) || substr($model->{$attribute}, 11, 1)  != $this->_getInnFactor($model->{$attribute}, self::$FACTOR_12)) {
                $model->addError($attribute, text('Неверный ИНН'));
                return;
            }
        }
    }
}
