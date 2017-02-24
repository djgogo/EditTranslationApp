<?php

namespace Translation\Controllers
{

    use Translation\Commands\UpdateTranslationFormCommand;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;

    class UpdateTranslationController implements ControllerInterface
    {
        /** @var TranslationTableDataGateway */
        private $dataGateway;

        /** @var UpdateTranslationFormCommand */
        private $updateTranslationFormCommand;

        public function __construct(
            UpdateTranslationFormCommand $updateTranslationFormCommand,
            TranslationTableDataGateway $dataGateway)
        {
            $this->dataGateway = $dataGateway;
            $this->updateTranslationFormCommand = $updateTranslationFormCommand;
        }

        public function execute(Request $request, Response $response)
        {
            if (!$this->updateTranslationFormCommand->execute($request)) {
                $response->setTranslation($this->dataGateway->findTranslationById($request->getValue('msgId')));
                return 'translations/editTranslation.twig';
            }

            $response->setTranslations(...$this->dataGateway->getAllTranslations());
            $response->setRedirect('/');
        }
    }
}
