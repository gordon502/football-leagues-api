<?php

namespace App\Common\Security;

use App\Modules\User\Provider\UserProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator as LexikJWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JWTAuthenticator extends LexikJWTAuthenticator
{
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $eventDispatcher,
        TokenExtractorInterface $tokenExtractor,
        UserProvider $userProvider,
        TranslatorInterface $translator = null
    ) {
        parent::__construct($jwtManager, $eventDispatcher, $tokenExtractor, $userProvider, $translator);
    }
}
