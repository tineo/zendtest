<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

use BjyAuthorize\View\RedirectionStrategy;
use Zend\EventManager\EventInterface;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $em  = $eventManager->getSharedManager();

        $em->attach(
            'ZfcUser\Form\RegisterFilter',
            'init',
            function( $e )
            {
                $filter = $e->getTarget();
                $filter->remove('password');
                $filter->remove('passwordVerify');
                // do your form filtering here
            }
        );

        // custom form fields

        $em->attach(
            'ZfcUser\Form\Register',
            'init',
            function($e)
            {
                /* @var $form \ZfcUser\Form\Register */

                $form = $e->getTarget();
                $form->get("display_name")->setLabel("Nombres y Apellidos");
                $form->remove('password');
                $form->remove('passwordVerify');

            }
        );

        // here's the storage bit

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        $zfcServiceEvents->attach('register', function($e) {
            $form = $e->getParam('form');
            $user = $e->getParam('user');

            /* Lazy Pass */
            $bcrypt = new \Zend\Crypt\Password\Bcrypt();
            $bcrypt->setCost($form->getRegistrationOptions()->getPasswordCost());
            $password = uniqid();
            $user->setPassword($bcrypt->create($password));

            /*Lazzy Mail*/
            $message = new Message();
            $message->addTo($user->getEmail())
                ->addFrom('unmailfulano@gmail.com')
                ->setSubject('Acceso a cuenta');

            // Setup SMTP transport using LOGIN authentication
            $transport = new SmtpTransport();
            $options   = new SmtpOptions(array(
                'host'              => 'smtp.gmail.com',
                'connection_class'  => 'login',
                'connection_config' => array(
                    'ssl'       => 'tls',
                    'username' => 'unmailfulano',
                    'password' => '996666567'
                ),
                'port' => 587,
            ));

            $html = new MimePart('Username:<br/>'.$user->getUsername().'<br/>Password:<br/><b>'.$password.'</b>');
            $html->type = "text/html";

            $body = new MimeMessage();
            $body->addPart($html);

            $message->setBody($body);

            $transport->setOptions($options);
            $transport->send($message);


        });

        // you can even do stuff after it stores
        $zfcServiceEvents->attach('register.post', function($e) {
            /*$user = $e->getParam('user');*/

        });
    }



    /*public function onBootstrap(EventInterface $e)
    {
        $application  = $e->getTarget();
        $eventManager = $application->getEventManager();

        $strategy = new RedirectionStrategy();

        // eventually set the route name (default is ZfcUser's login route)
        $strategy->setRedirectRoute('zfcuser/login');

        // eventually set the URI to be used for redirects
        //$strategy->setRedirectUri('http://example.org/login');

        $eventManager->attach($strategy);
    }*/

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
