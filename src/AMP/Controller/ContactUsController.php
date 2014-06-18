<?php
namespace AMP\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactUsController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        // magic strings, localization
        $controllers->match('/', function (Request $request) use ($app) {
            $notification = null;
            $formFactory = new \AMP\Form\ContactUsFormFactory($app['form.factory']);
            $form = $formFactory->getForm();
        
            if ($request->isMethod('POST')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $formDefault = $form->getData();
                    $email = new AMP\Mail();
                    $email->setRecipient('info@agilemusicproject.com')
                          ->setSubject($formDefault['subject'])
                          ->setMessage($formDefault['message'], $formDefault['name'])
                          ->setSender($formDefault['email']);
                    if ($email->send()) {
                        $notification = "Your message was sent successfully.";
                    } else {
                        $notification = "Your message was not sent. Please try again.";
                    }
                } else {
                    $formSubmit = "The form is invalid";
                }
            }
            return $app['twig']->render(
                'contact.twig',
                array('form' => $form->createView(),
                      'notification' => $notification)
            );
        });
        
        return $controllers;
    }
}