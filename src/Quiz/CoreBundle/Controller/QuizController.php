<?php

namespace Quiz\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\JsonArrayType;
use JMS\Serializer\SerializationContext;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\OngoingTest;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Quiz\CoreBundle\Entity\Result;
use Quiz\CoreBundle\Entity\TestResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;

class QuizController extends Controller
{
    /**
     * @param $id
     * @return RedirectResponse|Response
     */
    public function indexAction($id)
    {

        // current user
        $user = $this->getUser();


        // No quiz selected go back to main page
        // or show error page

       if ($id == 0) {
           return new RedirectResponse($this->get('router')->getContext()->getBaseUrl(), 301);
       }

        else {

            // Valid test
            // render the test page

            $em = $this->get('doctrine.orm.entity_manager');
            $quiz = $em->getRepository('QuizCoreBundle:Quiz')->find($id);



            // check if the quiz is public OR we have a user logged in

            if ($quiz->getIsPrivate() && !$user) {
                return new RedirectResponse($this->get('router')->getContext()->getBaseUrl(), 301);
            }


            $types = $quiz->getQuestions()->map(function($e) {
               return $e->getType();
            });


            $t = ['multiple' => 0, 'match' => 0, 'truefalse' => 0, 'essay' => 0];

            foreach ($types as $d) {
                if ($d == '') continue;
                $t[$d] = (int)$t[$d] + 1;
            }


            $t = array_filter($t,function($item){
                return $item != 0;
            });

            $time = $quiz->getTime();


            $message = 'Εξέταση';

            // render the page
           return  $this->render('@QuizCore/Quiz/quiz.html.twig', ['quiz' => $quiz, 'types' => $t,
               'time' => $time, 'message' => $message
           ]);

        }
    }


    public function startAction(Request $request) {

        $route = $this->get('request')->get('_route');
        $em = $this->get('doctrine.orm.entity_manager');
        $quizrep  = $em->getRepository('QuizCoreBundle:Quiz');
        $serializer = $this->get('serializer');
        $quiz = $quizrep->findOneBy(['id' => $request->get('id')]);

        $ongoingTest = new OngoingTest();

        $ongoingTest->setQuizId($quiz->getId());
        $ongoingTest->setUserId($this->getUser()->getId());
        $ongoingTest->setStartTime(new \DateTime());


        $em->persist($ongoingTest);
        $em->flush();

        return $this->render('@QuizCore/Quiz/quizStart.html.twig', ['quiz' => $quiz, 'sq' => $serializer->serialize($quiz, 'json'
        , SerializationContext::create()->setGroups(array('public'))), 'message' => 'Εξέταση']);
    }



    public function submitQuizAction(Request $request) {
        $results = $request->getContent();
        $em =  $this->get('doctrine.orm.entity_manager');

        $serializer = $this->get('serializer');

        $quizId  = $request->get('id');
        $quiz = $em->find('QuizCoreBundle:Quiz', $quizId);

        $ongoing = $em->getRepository('QuizCoreBundle:OngoingTest')->findBy(['quizId' => $quizId, 'userId' => $this->getUser()->getId()]);

        $lastIndex = count($ongoing) -1;

        if (count($ongoing) == 0) {
            return new Response('{"error":"test_not_started"}');
        } else if ($quiz->getTime() > 0) {
            // check the time

            $now = new \DateTime();

            if ($ongoing[$lastIndex]->getStartTime()->diff($now)->i > ($quiz->getTime() + 1) ) {
                $em->remove($ongoing[$lastIndex]);
                return new Response( '{"error" : "times_up"}' );
            }



        }

        if (!isset($quiz)) {
            return new Response('error: Quiz not found');
        } else {

            $resultObject = json_decode($results);
            $response = [];


            $testResult = new TestResult();

            $testResult->setQuiz($quiz);
            $testResult->setUser($this->getUser());
            $testResult->setDate(new \DateTime());

            $now = new \DateTime();

            $testResult->setTestDuration($ongoing[$lastIndex]->getStartTime()->diff($now)->i);

            $totalGrade = 0;

            foreach($quiz->getQuestions() as $question) {
                /* @var $question Question */

                foreach ($resultObject as $result) {
                    if ($result->questionId == $question->getId()) {

                        switch($result->type) {
                            case 'multiple':
                                $correctAnswersIds = $question->getAnswers()->filter(function($item) {
                                    /* @var $item Answer */
                                    return $item->getIsCorrect() == true;
                                })->map(function($item) {return $item->getId();})->toArray();

                                foreach ($result->answer as $userAnswerId) {

                                    // log the user answers

                                    $thisAnswer = $question->getAnswers()->filter(function($item) use($userAnswerId){
                                        return $item->getId() == $userAnswerId;
                                    })->first();


                                    if (in_array($userAnswerId, $correctAnswersIds)) {
                                        // correct answer
                                        $toAdd = new Result();
                                        $toAdd->setQuestion($question);
                                        $toAdd->setAnswer($thisAnswer);
                                        $toAdd->setIsCorrect(true);
                                        $toAdd->setDegree($question->getCorrectAnswerGrade());

                                        $testResult->addResult($toAdd);

                                    }else {
                                        // wrong answer
                                        $wrongAnswerAdd = new Result();

                                        $wrongAnswerAdd->setQuestion($question);
                                        $wrongAnswerAdd->setAnswer($thisAnswer);
                                        $wrongAnswerAdd->setIsCorrect(false);


                                        if ($quiz->getHasNegativeGrade()) {
                                            $wrongAnswerAdd->setDegree($question->getWrongAnswerGrade() * -1);
                                        } else {
                                            $wrongAnswerAdd->setDegree(0);
                                        }

                                        $testResult->addResult($wrongAnswerAdd);

                                    }
                                }






                                if ( count($result->answer) == count($correctAnswersIds) ) {

                                    // assume every answer is correct
                                     $isCorrect = true;



                                    foreach($result->answer as $ua) {
                                        if (! in_array($ua, $correctAnswersIds)) {
                                            // we found a wrong answer, take it down
                                            $isCorrect = false;
                                        }
                                    }

                                    if ($isCorrect) {
                                        $totalGrade += $question->getCorrectAnswerGrade();
                                    } else {

                                        if ($quiz->getHasNegativeGrade()) {
                                            $totalGrade -= $question->getWrongAnswerGrade();
                                        }

                                    }


                                } else {
                                    // wrong answer
                                    if ($quiz->getHasNegativeGrade()) {
                                        $totalGrade -= $question->getWrongAnswerGrade();
                                    }
                                }




                                break;
                        }

                    }

                }
            }

            if ($totalGrade <=0) {
                $testResult->setDegree(0);
            } else {
                $testResult->setDegree($totalGrade);
            }

            if ($totalGrade >= $quiz->getPassGrade()) {
                $testResult->setIsPassed(true);
            } else {
                $testResult->setIsPassed(false);
            }


        }


        $em->remove($ongoing[$lastIndex]);

        $testResult->setCompleted(true);
        $em->persist($testResult);
        $em->flush();


        return new JsonResponse('{"resultObject":' .$serializer->serialize($testResult, "json"). ', "questions":' .
            $serializer->serialize($quiz->getQuestions(), "json") . '}');
    }





}