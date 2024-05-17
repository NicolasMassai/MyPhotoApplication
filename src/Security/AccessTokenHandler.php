<?php
namespace App\Security;

use App\Repository\UserRepository;
use App\Repository\AccessTokenRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserRepository $repository,
        private JWTEncoderInterface $jWTTokenInterface
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        // e.g. query the "access token" database to search for this token
        if (null === $accessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        $payloadToken = $this->jWTTokenInterface->decode($accessToken);

        // and return a UserBadge object containing the user identifier from the found token
        // (this is the same identifier used in Security configuration; it can be an email,
        // a UUUID, a username, a database ID, etc.)
        return new UserBadge($payloadToken['username']);
    }
}