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

class Rs extends Validator {

    static $LENGTH = 20;
    static $FACTOR = [7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1];

    public $bikAttribute = 'bik';

    private function _getRsFactor($value, $factor) {
        $sum = 0;
        $number = count($factor);
        for ($i = 0; $i != $number; $i++) {
            $sum += ($value[$i] * $factor[$i]) % 10;
        }
        return $sum % 10 == 0;
    }

    public function validateAttribute($model, $attribute) {
        if (!$model->hasAttribute($this->bikAttribute)) {
                $model->addError($attribute, Yii::t('app', 'Для проверки поля "{attribute}" необходимо заполнить БИК', [
                    'attribute' => $model->getAttributeLabel($attribute)
                ]));
            return;
        }

        if ($model->hasErrors($this->bikAttribute)) {
            $model->addError($attribute, $model->getErrors($this->bikAttribute));
            return;
        }

        $length = strlen($model->{$attribute});

        if ($length != self::$LENGTH) {
            $model->addError($attribute, Yii::t('app', '{attribute} должен быть длинной {length} символов', ['attribute' => $model->getAttributeLabel($attribute), 'length' => self::$LENGTH]));
            return;
        }
        if (!$this->_getRsFactor(substr($model->{$this->bikAttribute}, -3, 3).$model->{$attribute}, self::$FACTOR)) {
               
            $model->addError($attribute, Yii::t('app', 'Неправильный {attribute} или {bik}', [
                'attribute' =>$model->getAttributeLabel($attribute),
                'bik'=>$model->getAttributeLabel($this->bikAttribute)
            ]));
            return;
        }
    }

}
