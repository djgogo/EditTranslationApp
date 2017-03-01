<?php

namespace Translation\GetText
{
    class PoParserFrench extends AbstractPoParser
    {
        protected function addToEntries(string $msgId, string $value)
        {
            /** @var $entry GetTextEntry $entry */
            foreach ($this->getEntries() as $entry) {
                if ($entry->getMsgId() === $msgId) {
                    $entry->setMsgFrench($value);
                }
            }
        }
    }
}
