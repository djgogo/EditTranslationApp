<?php

namespace Translation\GetText
{
    class PoParserGerman extends AbstractPoParser
    {
        protected function addToEntries(string $msgId, string $value)
        {
            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId($msgId);
            $getTextEntry->setMsgGerman($value);

            $this->addGetTextEntry($getTextEntry);
        }
    }
}
