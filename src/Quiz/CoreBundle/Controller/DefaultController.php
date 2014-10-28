<?php


namespace Quiz\CoreBundle\Controller;


use Quiz\CoreBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {


        return $this->render('QuizCoreBundle:Default:index.html.twig', array("haha" => "ti re?"));
    }
}