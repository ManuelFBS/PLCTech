<?php

namespace PLCTech\Domain\ValueObjects;

class Email
{
        private string $value;

        public function __construct(string $email)
        {
                if (!$this->isValid($email)) {
                        throw new \InvalidArgumentException('Formato de email inválido');
                }
                $this->value = $email;
        }

        private function isValid(string $email): bool
        {
                return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }

        public function getValue(): string
        {
                return $this->value;
        }

        public function __toString(): string
        {
                return $this->value;
        }
}
