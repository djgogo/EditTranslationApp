<?php

namespace Translation\GetText
{
    class GetTextEntry
    {
        /** @var string */
        private $msgId;

        /** @var string */
        private $msgStrGerman;

        /** @var string */
        private $msgStrFrench;

        /** @var \DateTime */
        private $importDate;

        /** @var \DateTime */
        private $updated;

        public function getMsgId(): string
        {
            return $this->msgId;
        }

        public function setMsgId($msgId)
        {
            $this->msgId = $msgId;
        }

        public function getMsgStrGerman(): string
        {
            return $this->msgStrGerman;
        }

        public function setMsgStrGerman($msgStrGerman)
        {
            $this->msgStrGerman = $msgStrGerman;
        }

        public function getMsgStrFrench(): string
        {
            return $this->msgStrFrench;
        }

        public function setMsgStrFrench($msgStrFrench)
        {
            $this->msgStrFrench = $msgStrFrench;
        }

        public function getImportDate(): \DateTime
        {
            return $this->importDate;
        }

        public function getUpdated(): \DateTime
        {
            return $this->updated;
        }
    }
}
