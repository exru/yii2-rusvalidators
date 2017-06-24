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

class Kpp extends \yii\validators\Validator{
    
    const MASK = '/^\d{4}[\dA-Z][\dA-Z]\d{3}$/u';
    
    public function validateAttribute($model, $attribute) {
        
        if (!preg_match(self::MASK, $model->{$attribute})) {
            $model->addError($attribute, Yii::t('app', 'Неверный формат КПП'));
            return;
        }
    }
}

