<?php

namespace Translation\Authentication {

    use Translation\Exceptions\UserTableGatewayException;
    use Translation\Gateways\UserTableDataGateway;
    use Translation\ParameterObjects\UserParameterObject;

    class Registrator
    {
        /** @var UserTableDataGateway */
        private $userGateway;

        public function __construct(UserTableDataGateway $userGateway)
        {
            $this->userGateway = $userGateway;
        }

        public function register(UserParameterObject $userParameter): bool
        {
            try {
                $this->userGateway->insert($userParameter);
                return true;
            } catch (UserTableGatewayException $e) {
                return false;
            }
        }

        public function usernameExists(string $username): bool
        {
            return $this->userGateway->findUserByUsername($username);
        }
    }
}
