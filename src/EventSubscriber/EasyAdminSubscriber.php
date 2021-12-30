<?php

namespace App\EventSubscriber;

use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Product;
use App\Security\EmailVerifier;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\Mime\Address;
use PhpParser\Node\Expr\Instanceof_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    
    private EmailVerifier $emailVerifier;
    private UserPasswordHasherInterface $userPasswordHasher;
    
    private $password ;

    public function __construct(EmailVerifier $emailVerifier, UserPasswordHasherInterface $userPasswordHasher, $password = null )
    {
        $this->emailVerifier = $emailVerifier;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->password = $password;
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if($entity instanceof User){

            $this->password = uniqid();
            $password = $this->userPasswordHasher->hashPassword($entity,$this->password);
            
            $entity->setPassword($password);
            $entity->setRoles($entity->getRoles());
            
        }


        if($entity instanceof Product){
                
            $date = new \DateTimeImmutable;
            $entity->setCreatedAt($date);

        }

        
    }

    public function onAfterEntityPersistedEvent(AfterEntityPersistedEvent $event)
    {

        $entity = $event->getEntityInstance();

        if($entity instanceof User){

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $entity,
                (new TemplatedEmail())
                    ->from(new Address($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']))
                    ->to($entity->getEmail())
                    ->subject('Please Confirm your Email')
                    ->context(['password' => $this->password])
                    ->htmlTemplate('registration/confirmation_user_email.html.twig') );

        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
            AfterEntityPersistedEvent::class => 'onAfterEntityPersistedEvent'
        ];
    }
}
