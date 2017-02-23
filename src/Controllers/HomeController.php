<?php

namespace Translation\Controllers
{

    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    class HomeController implements ControllerInterface
    {
        /** @var Session */
        private $session;

        /** @var TranslationTableDataGateway */
        private $dataGateway;

        public function __construct(Session $session, TranslationTableDataGateway $dataGateway)
        {
            $this->session = $session;
            $this->dataGateway = $dataGateway;
        }

        public function execute(Request $request, Response $response): string
        {
            if ($request->hasValue('sort')) {
                if ($request->getValue('sort') === 'ASC') {
                    $response->setTranslations(...$this->dataGateway->getAllTranslationsOrderedByUpdated('ASC'));
                } elseif ($request->getValue('sort') === 'DESC') {
                    $response->setTranslations(...$this->dataGateway->getAllTranslationsOrderedByUpdated('DESC'));
                }
            } else {
                $response->setTranslations(...$this->dataGateway->getAllTranslations());
            }

            if ($request->hasValue('search')) {
                $response->setTranslations(...$this->dataGateway->getSearchedTranslation($request->getValue('search')));
            }

            return 'home.twig';
        }
    }
}
