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

        $ong = $doctrine->getRepository("QuizCoreBundle:OngoingTest");
        $qz = $doctrine->getRepository("QuizCoreBundle:Quiz");



        $first  = $ong->find(1);
        $quiz = $qz->findOneBy(["id" => $first->getQuizId()]);
        $questions = $quiz->getQuestions();


        return new Response($questions->get(0));
    }


    public function addQuizAction() {


        $quiz = new Quiz();
        $quiz->setName("test quiz");

        $question = new Question();

        $question->setQuestionText("Ποια είναι η πρωτεύουσα της Ελλάδας?");
        $answer = new Answer();
        $answer2 = new Answer();

        $answer->setAnswerText("Αθήνα");
        $answer2->setAnswerText("Θεσσαλονίκη");

        $question->addAnswer($answer);
        $question->addAnswer($answer2);

        $quiz->addQuestion($question);


        $doctrine = $this->get("doctrine.orm.entity_manager");
        $doctrine->persist($quiz);
        $doctrine->flush();



        return new Response("Adding");
    }

    public function teacherAction() {
        return new Response("Hello teacher");
    }
}