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

class Okpo extends Validator {

    static $LENGTH = 8;
    static $FACTOR = [1,2,3,4,5,6,7];
    static $FACTOR_2 = [3,4,5,6,7,8,9];

    public $bikAttribute = 'bik';

    private function _getOkpoFactor($value, $factor) {
        $sum = 0;
        $number = count($factor);
        for ($i = 0; $i != $number; $i++) {
            $sum += ($value[$i] * $factor[$i]);
        }
        return $sum % 11;
    }

    public function validateAttribute($model, $attribute) {
        $length = strlen($model->{$attribute});

        if ($length != self::$LENGTH) {
            $model->addError($attribute, Yii::t('app', '{attribute} должен быть длинной {length} символов', ['attribute' => $model->getAttributeLabel($attribute), 'length' => self::$LENGTH]));
            return;
        }
        
        $factor = $this->_getOkpoFactor($model->{$attribute}, self::$FACTOR);
        
        $factor2 = $this->_getOkpoFactor($model->{$attribute}, self::$FACTOR_2);
        
        $factor2 = $factor2 == 10?0:$factor2;
        
        $value = substr($model->{$attribute}, 7 , 1);
        
        if ($factor > 9 && ($value == $factor2)) {
           return;
        }elseif($value == $factor){
            return;
        }
            $model->addError($attribute, Yii::t('app', 'Неправильный {attribute}', [
                'attribute' => $model->getAttributeLabel($attribute)]));
    }

}
