<?php


namespace Quiz\CoreBundle\Controller;


use Quiz\CoreBundle\Engine\QuizEngine\QuizTest;
use Quiz\CoreBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $tm = $this->get('translator')->trans('Symfony is great');


        return $this->render('@QuizCore/Default/index.html.twig', ['translated_message' => $tm]);
    }

    public function testAction()
    {

        $en = $this->container->get('doctrine')->getRepository('QuizCoreBundle:UserEntity')->find(1);


        $a = $this->get('security.context');


        return $this->render('@QuizCore/Default/index.html.twig');
    }
}