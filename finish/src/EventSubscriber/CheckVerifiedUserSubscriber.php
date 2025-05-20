<?php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber{
    
    public function onCheckPassport(CheckPassportEvent $event){
      $passport = $event->getPassport();
      if(!$passport instanceof UserPassportInterface){
        throw new \Exception('unexpected passport type');
      }

      $user = $passport->getUser();
      if(!$user instanceof User){
        throw new \Exception('unexpected user type');
      }
      if(!$user->getIsVerified()){
        throw new AccountNotVerifiedAuthenticationException();
      }
}

    public static function getSubscribedEvents(){
        return [
            CheckPassportEvent::class => ['onCheckPassport',-10],
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }
}