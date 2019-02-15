<?php
namespace Mbp\SecurityBundle\Controller;

use AppBundle\Security\ApiKeyUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey)
    {
        // look for an apikey query parameter
        $apiKey = "";


        // or if you want to use an "apikey" header, then do something like this:
        $apiKey = $request->headers->get('apikey');

        if (!$apiKey) { 
            //throw new BadCredentialsException();
            // or to just skip api key authentication            
            return null;
        }
        
        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        // print_r($userProvider);
        // print_r($token);
        // if (!$userProvider instanceof ApiKeyUserProvider) {
        //     throw new \InvalidArgumentException(
        //         sprintf(
        //             'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
        //             get_class($userProvider)
        //         )
        //     );
        // }
        $apiKey = $token->getCredentials();
        \var_dump($apiKey);
        exit;
        $username = $userProvider->getUsernameForApiKey($apiKey);

        if (!$username) {
            throw new AuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }

        $user = $userProvider->loadUserByUsername($username);
        
        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication Failed.", 401);
    }
}