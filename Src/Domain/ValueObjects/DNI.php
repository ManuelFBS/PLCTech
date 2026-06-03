<?php

namespace PLCTech\Domain\ValueObjects;

class DNI
{
        private string $value;

        public function __construct(string $dni)
        {
                // > Validación básica de DNI (ajusta según tu país)...
                if (!preg_match('/^[a-zA-Z0-9-]+$/', $dni)) {
                        throw new \InvalidArgumentException('DNI inválido: $dni');
                }

                $this->value = $dni;
        }

        public function value(): string
        {
                return $this->value;
        }
}

?>