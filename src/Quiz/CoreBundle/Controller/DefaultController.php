<?php


namespace Quiz\CoreBundle\Controller;


use Quiz\CoreBundle\Engine\QuizEngine\QuizTest;
use Quiz\CoreBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('QuizCoreBundle:Default:index.html.twig', array("haha" => "ti re?"));
    }

    public function testAction()
    {

         $obj = new QuizTest();

        return new Response($this->get('security.context')->getToken());
    }
}