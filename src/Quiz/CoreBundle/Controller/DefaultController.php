<?php


namespace Quiz\CoreBundle\Controller;


use Quiz\CoreBundle\Engine\QuizEngine\QuizTest;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('@QuizCore/Default/index.html.twig', ['locale' => 'nai']);
    }

    public function testAction()
    {

        $em = $this->get('doctrine')->getManager();
        $quiz = $this->get('doctrine')->getRepository("QuizCoreBundle:Quiz")
        ->find(1);

       return new Response();
    }

    public function teacherAction() {
        return new Response("Hello teacher");
    }
}