<?php

namespace Translation\GetText
{
    class GetTextEntry
    {
        /** @var string */
        private $msgId;

        /** @var string */
        private $msgGerman;

        /** @var string */
        private $msgFrench;

        /** @var \DateTime */
        private $created;

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

        public function getMsgGerman(): string
        {
            return $this->msgGerman;
        }

        public function setMsgGerman($msgStrGerman)
        {
            $this->msgGerman = $msgStrGerman;
        }

        public function getMsgFrench(): string
        {
            return $this->msgFrench;
        }

        public function setMsgFrench($msgStrFrench)
        {
            $this->msgFrench = $msgStrFrench;
        }

        public function getCreated(): \DateTime
        {
            return $this->created;
        }

        public function setCreated($created)
        {
            $this->created = $created;
        }

        public function getUpdated(): \DateTime
        {
            return $this->updated;
        }

        public function setUpdated($updated)
        {
            $this->updated = $updated;
        }
    }
}
