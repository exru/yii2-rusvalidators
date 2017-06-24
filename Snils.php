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

class Snils extends Validator {

    static $LENGTH = 11;
    static $MIN_VALUE = 1001998;
    static $FACTOR = [9, 8, 7, 6, 5, 4, 3, 2, 1];

    private function _getSnilsFactor($value, $factor) {
        $sum = 0;
        $number = count($factor);
        for ($i = 0; $i != $number; $i++) {
            $sum += $value[$i] * $factor[$i];
        }
        if ($sum < 100) {
            return $sum;
        }
        if ($sum == 100 || $sum == 101) {
            return 0;
        }
        if ($sum > 101) {
            return $sum % 101;
        }
    }

    public function validateAttribute($model, $attribute) {
        if (intval($model->{$attribute}) < self::$MIN_VALUE) {
            $model->addError($attribute, Yii::t('app', 'СНИЛС должен быть больше {number}', ['number' => self::$MIN_VALUE]));
            return;
        }
        $length = strlen($model->{$attribute});

        if ($length != self::$LENGTH) {
            $model->addError($attribute, Yii::t('app', 'СНИЛС должен быть длинной {length} символов', ['length' => self::$LENGTH]));
            return;
        }
        if (substr($model->{$attribute}, -2, 2) != $this->_getSnilsFactor($model->{$attribute}, self::$FACTOR)) {
            $model->addError($attribute, Yii::t('app', 'Неверный СНИЛС'));
            return;
        }
    }

}
