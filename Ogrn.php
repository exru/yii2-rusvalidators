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

use Yii;
use yii\validators\Validator;

class Ogrn extends Validator{
    const FACTOR_13 = 13;
    const FACTOR_15 = 15;
    
    private function _getOgrnFactor($value, $factor) {
        $sum = intVal(floor(($value / 10) % ($factor - 2)));
        $dig = intVal(substr($value, $factor-1, 1));
        return ($sum == 10 ?0:$sum) == $dig;
        
    }
    
    public function validateAttribute($model, $attribute)
    {
        $length = strlen($model->{$attribute});

        if ($length != self::FACTOR_13 && $length != self::FACTOR_15) {
            $model->addError($attribute, Yii::t('app', 'ОГРН должен быть длинной 13 или 15 символов'));
            return;
        }
        if ($length == self::FACTOR_13) {
            if (!$this->_getOgrnFactor($model->{$attribute}, self::FACTOR_13)) {
                $model->addError($attribute, Yii::t('app', 'Неверный ОГРН'));
                return;
            }
        }
        if ($length == self::FACTOR_15) {
            if (!$this->_getOgrnFactor($model->{$attribute}, self::FACTOR_15)) {
                $model->addError($attribute, Yii::t('app', 'Неверный ОГРН'));
                return;
            }
        }
    }
}
