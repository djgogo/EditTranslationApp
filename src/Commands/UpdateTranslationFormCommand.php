<?php

namespace Translation\Commands
{

    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Forms\FormError;
    use Translation\Forms\FormPopulate;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Session;
    use Translation\Http\Request;
    use Translation\ParameterObjects\TranslationParameterObject;
    use Translation\ValueObjects\MsgId;

    class UpdateTranslationFormCommand extends AbstractFormCommand
    {
        /** @var TranslationTableDataGateway */
        private $dataGateway;

        /** @var FormPopulate */
        private $populate;

        /** @var FormError */
        private $error;

        /** @var string */
        private $msgId;

        /** @var string */
        private $msgGerman;

        /** @var string */
        private $msgFrench;

        /** @var \DateTime */
        private $dateTime;

        public function __construct(
            Session $session,
            TranslationTableDataGateway $dataGateway,
            FormPopulate $formPopulate,
            FormError $error,
            \DateTime $dateTime)
        {
            parent::__construct($session);

            $this->dataGateway = $dataGateway;
            $this->populate = $formPopulate;
            $this->error = $error;
            $this->dateTime = $dateTime;
        }

        protected function setFormValues(Request $request)
        {
            $this->msgId = $request->getValue('msgId');
            $this->msgGerman = trim($request->getValue('msgGerman'));
            $this->msgFrench = trim($request->getValue('msgFrench'));
        }

        protected function validateRequest()
        {
            try {
                new MsgId($this->msgId);
            } catch (\InvalidArgumentException $e) {
                $this->error->set('msgId', 'Die Translations-Id ist ungültig.');
            }

            if (strlen($this->msgGerman) > 1024) {
                $this->error->set('msgGerman', 'Der Text darf nicht länger als 1024 Zeichen sein.');
            }

            if ($this->msgGerman === '') {
                $this->error->set('msgGerman', 'Bitte geben Sie einen Deutschen Übersetzungstext ein.');
            }

            if (strlen($this->msgFrench) > 1024) {
                $this->error->set('msgFrench', 'Der Text darf nicht länger als 1024 Zeichen sein.');
            }

            if ($this->msgFrench === '') {
                $this->error->set('msgFrench', 'Bitte geben Sie einen Französischen Übersetzungstext ein.');
            }
        }

        protected function performAction()
        {
            $translation = new TranslationParameterObject(
                $this->msgId,
                $this->msgGerman,
                $this->msgFrench,
                $this->dateTime->format('Y-m-d H:i:s')
            );

            try {
                $this->dataGateway->update($translation);
                $this->getSession()->setValue('message', 'Datensatz wurde geändert.');
            } catch (TranslationTableGatewayException $e) {
                $this->getSession()->setValue('warning', 'Änderung fehlgeschlagen!');
            }
        }

        protected function repopulateForm()
        {
            if ($this->msgGerman !== '') {
                $this->populate->set('msgGerman', $this->msgGerman);
            }

            if ($this->msgFrench !== '') {
                $this->populate->set('msgFrench', $this->msgFrench);
            }
        }
    }
}
