<?php

namespace Translation\ValueObjects
{

    use Translation\Exceptions\InvalidIdException;

    class MsgId
    {
        /** @var string */
        private $id;

        public function __construct(string $id)
        {
            $this->ensureIdNumberIsNotEmpty($id);
            $this->ensureIdIsNotExceedingMaxLength($id);
            $this->id = $id;
        }

        private function ensureIdNumberIsNotEmpty(string $id)
        {
            if ($id === '') {
                throw new InvalidIdException('Id: "' . $id . '" darf nicht leer sein.');
            }
        }

        private function ensureIdIsNotExceedingMaxLength(string $id)
        {
            if (strlen($id) > 255) {
                throw new InvalidIdException('Id: "' . $id . '" ist zu lang.');
            }
        }

        public function __toString(): string
        {
            return $this->id;
        }
    }
}
