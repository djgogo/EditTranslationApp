<?php

namespace Translation\Forms
{
    interface FormInterface
    {
        public function set(string $key, string $value);
        public function has(string $key): bool;
        public function remove(string $key);
        public function get(string $key): string;
    }
}
