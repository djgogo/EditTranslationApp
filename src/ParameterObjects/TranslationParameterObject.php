<?php

namespace Translation\ParameterObjects {

    class TranslationParameterObject
    {
        /** @var string */
        private $msgId;

        /** @var string */
        private $msgGerman;

        /** @var string */
        private $msgFrench;

        /** @var string */
        private $created;

        /** @var string */
        private $updated;

        public function __construct(string $msgId, string $msgGerman, string $msgFrench, string $created, string $updated)
        {
            $this->msgId = $msgId;
            $this->msgGermn = $msgGerman;
            $this->msgFrench = $msgFrench;
            $this->created = $created;
            $this->updated = $updated;
        }

        public function getMsgId(): string
        {
            return $this->msgId;
        }

        public function getMsgGerman(): string
        {
            return $this->msgGerman;
        }

        public function getMsgFrench(): string
        {
            return $this->msgFrench;
        }

        public function getCreated(): string
        {
            return $this->created;
        }

        public function getUpdated(): string
        {
            return $this->updated;
        }
    }
}
