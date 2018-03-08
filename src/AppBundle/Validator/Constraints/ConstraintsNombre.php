<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsNombre extends Constraint {

    public $message = 'mensaje.error.validar.nombre';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
