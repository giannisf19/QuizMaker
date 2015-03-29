<?php


namespace Quiz\CoreBundle\Controller;


use Quiz\CoreBundle\Engine\QuizEngine\QuizTest;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\OngoingTest;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Quiz\CoreBundle\Engine\QuizInfo;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('@QuizCore/Default/index.html.twig', ['locale' => 'nai']);
    }

    public function testAction()
    {
        $doctrine = $this->get("doctrine.orm.entity_manager");


       return new JsonResponse("{date: " . (new \DateTime("now"))->format('h') . "}");

    }


    public function addQuizAction() {



    }

    public function teacherAction() {
        return new Response("Hello teacher");
    }
}