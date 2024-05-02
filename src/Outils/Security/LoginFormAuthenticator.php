<?php

namespace App\Outils\Security;

use App\Entity\Compte;
use App\Entity\User;
use App\Outils\CompteOutils;
use App\Repository\CompteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Outils\Security\PasswordManagement;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    private CompteRepository $userRepository;
    private PasswordManagement $passwordManagement;
    private CompteOutils $compteOutils;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(CompteRepository $userRepository, PasswordManagement $passwordManagement, CompteOutils $compteOutils, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->passwordManagement = $passwordManagement;
        $this->compteOutils = $compteOutils;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('email');

        $numLicencie = $this->compteOutils->getNumeroDeLicence($email);

        $password = $request->request->get('password');

        $isOkay = $this->passwordManagement->verifierMDP($numLicencie, $password);

        // Capturer $isOkay dans la portée de la fonction de rappel
        $isOkayClosure = $isOkay;

        return new Passport(
            new UserBadge($email, function($userIdentifier) use ($isOkayClosure) {
                // Access $isOkayClosure here
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {
                    throw new UserNotFoundException();
                }
                return $user;
            }),
            new CustomCredentials(function($credentials, Compte $user) use ($isOkayClosure) {
                return $isOkayClosure;
            }, $password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $url = $this->urlGenerator->generate('accueil');

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dd('failure');
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntrypointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}