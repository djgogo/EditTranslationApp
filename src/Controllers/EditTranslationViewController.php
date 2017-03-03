<?php

namespace Translation\Controllers
{

    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Forms\FormPopulate;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    class EditTranslationViewController
    {
        /** @var TranslationTableDataGateway */
        private $dataGateway;

        /** @var Session */
        private $session;

        /** @var FormPopulate */
        private $populate;

        public function __construct(
            Session $session,
            TranslationTableDataGateway $dataGateway,
            FormPopulate $formPopulate
        )
        {
            $this->dataGateway = $dataGateway;
            $this->session = $session;
            $this->populate = $formPopulate;
        }

        public function execute(Request $request, Response $response)
        {
            if ($request->hasValue('msgId') && $request->getValue('msgId') === '') {
                return 'templates/errors/404.twig';
            }

            try {
                $response->setTranslation($this->dataGateway->findTranslationById($request->getValue('msgId')));
            } catch (TranslationTableGatewayException $e) {
                return 'templates/errors/500.twig';
            }

            $this->populate->set('msgGerman', $response->getTranslation()->getMsgGerman());
            $this->populate->set('msgFrench', $response->getTranslation()->getMsgFrench());

            if ($this->session->isset('error')) {
                $this->session->deleteValue('error');
            }

            return 'translations/editTranslation.twig';
        }
    }
}
