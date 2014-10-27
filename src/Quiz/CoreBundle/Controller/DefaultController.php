<?php


namespace Quiz\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('QuizCoreBundle:Default:index.html.twig');
    }
}