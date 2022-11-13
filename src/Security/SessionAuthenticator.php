<?php

namespace App\Security;

use App\Controller\ErrorController;
use App\Entity\User;
use App\Security\Contract\UserFinderByIdQueryInterface;
use Predis\Client as Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

//use Psr\Log\LoggerInterface;

class SessionAuthenticator extends AbstractAuthenticator
{
    private const SESS_COOKIE = 'sessid';

    public function __construct(
        private readonly Redis                        $redis,
//        private readonly LoggerInterface $logger,
        private readonly UserFinderByIdQueryInterface $userFinder,
        private readonly ErrorController $errorController
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->cookies->has(self::SESS_COOKIE);
    }

    public function authenticate(Request $request): Passport
    {
        $sessid = (string) $request->cookies->get(self::SESS_COOKIE);

        return new SelfValidatingPassport(new UserBadge($sessid, $this->userLoader(...)));
    }

    /**
     * @throws AuthenticationException
     */
    private function userLoader(string $token): ?User
    {
        $id = (string) $this->redis->get(self::SESS_COOKIE . ":$token");

        return $this->userFinder->__invoke($id);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->errorController->__invoke($exception);
    }
}
