<?php

namespace Prime\Bundle\TranslationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PrimeTranslationsBundle:Default:index.html.twig');
    }
}
