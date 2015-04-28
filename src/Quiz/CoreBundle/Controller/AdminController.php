<?php

namespace Quiz\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Proxies\__CG__\Quiz\CoreBundle\Entity\UserEntity;
use Quiz\CoreBundle\Engine\QuizInfo;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    public function editOrCreateQuizAction($id) {



        /* @var $user UserEntity*/
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $this->getUser();

        if (! isset($user)) {
            return new RedirectResponse($this->get('router')->generate('fos_user_security_login'));
        }


       $hisQuiz = false;
       $isTeacher = in_array('ROLE_TEACHER', $user->getRoles());
        $message = 'Καθηγητής';


        if ($isTeacher && !isset($id)) {
            // create new quiz

            $q = $em->find('QuizCoreBundle:Quiz', $id);

            $action = 'Δημιουργία';
            return $this->render('@QuizCore/Teacher/editQuiz.html.twig', ['quiz' => $q, 'message' => $message, 'action' => $action ]);
        }

        foreach ($user->getQuizes() as $quiz) {
            /* @var $quiz Quiz */

            if ($quiz->getId() == $id) {
                $hisQuiz = true;
            }
        }




        if ($isTeacher && $hisQuiz) {
            // the user is a TEACHER and the quiz belongs to him

            $quiz = $em->getRepository('QuizCoreBundle:Quiz')->findOneBy(['id'=> $id]);


            $action = 'Επεξεργασία';

            return $this->render('@QuizCore/Teacher/editQuiz.html.twig', ['quiz' => $quiz, 'message' => $message, 'action' => $action]);

        } else {
            return new Response("error");
        }



    }

    /**
     * @return Response
     */
    public function saveQuizAction()
    {


        /* @var $user \Quiz\CoreBundle\Entity\UserEntity */
        $user = $this->getUser();
        $serializer = $this->get('serializer');
        $em = $this->get('doctrine.orm.entity_manager');


        if (!isset($user)) {
            return new Response('Access Denied');
        } else {
            $roles = $user->getRoles();

            if (in_array('ROLE_TEACHER', $roles)) {
                // The user is a teacher, he can edit a quiz

                $toSave  = $this->get('request')->getContent();
                $jsonObject = json_decode($toSave);


                $quiz = $em->find('QuizCoreBundle:Quiz', $jsonObject->id);


                if (isset($quiz)) {
                    // existing quiz

                    $quiz->setName($jsonObject->name);
                    $quiz->setIsDisabled($jsonObject->is_disabled);
                    $quiz->setIsPrivate($jsonObject->is_private);
                    $quiz->setTime($jsonObject->time);


                    $qids = array_map(function($item){
                        return $item->id;
                    }, $jsonObject->questions);



                    foreach($jsonObject->questions as $question) {
                        /* @var $question object */

                        $currentQId=  $question->id;

                        $currentQuestionsFromDb = null;

                        foreach ($quiz->getQuestions() as $qq) {
                            /* @var $qq Question */


                             if (! in_array($qq->getId(), $qids)) {
                                $quiz->removeQuestion($qq);
                            }

                            if ($qq->getId() == $question->id) {
                                $currentQuestionsFromDb = $qq;
                            }
                        }

                        if ($question->id == 0) {
                            // new question to insert

                        }

                       else {

                            $currentQuestionsFromDb->setQuestionText($question->question_text);
                            $currentQuestionsFromDb->setType($question->type);
                            $currentQuestionsFromDb->setOrder($question->order);

                        }


                        $aids  = array_map(function($item){
                            return $item->id;
                        }, $question->answers);




                        foreach($question->answers as $answer) {
                            /* @var $answer object */


                            $currentAId = $answer->id;

                            /* @var $currentAnswerFromDb Answer */
                            $currentAnswerFromDb = null;

                             if ($currentQuestionsFromDb != null) {
                                 foreach($currentQuestionsFromDb->getAnswers() as $aa) {
                                     /* @var $aa Answer */


                                     if  (! in_array($aa->getId(), $aids)) {
                                         //remove
                                         $currentQuestionsFromDb->removeAnswer($aa);
                                     }

                                     if ($aa->getId() == $answer->id) {
                                         $currentAnswerFromDb = $aa;
                                     }
                                 }
                             }

                            if ($answer->id == 0) {
                                // new answer to insert
                                $answerToAdd = new Answer();
                                $answerToAdd->setAnswerText($answer->answer_text);
                                $answerToAdd->setLeftOrRight($answer->left_or_right);
                                $answerToAdd->setIsCorrect($answer->is_correct);
                                $answerToAdd->setQuestion($currentQuestionsFromDb);


                            } else {
                                //edit
                                $currentAnswerFromDb->setAnswerText($answer->answer_text);
                                $currentAnswerFromDb->setLeftOrRight($answer->left_or_right);
                                $currentAnswerFromDb->setIsCorrect($answer->is_correct);
                            }
                        }

                    }


                } else {
                    // create new quiz


                }





                $em->flush();
                return new Response('ok');






//                $quiz =  new Quiz();
//
//                $quiz->setName("test quiz");
//                $quiz->setIsDisabled(false);
//                $quiz->setTime(60);
//                $quiz->setOwners($user);
//
//                $nq = new Question();
//
//                $nq->setQuestionText('poso kanei 1 + 1')
//                    ->setOrder(4)
//                    ->setType('multiple');
//
//                $na = new Answer();
//
//                $na->setAnswerText('2')
//                    ->setIsCorrect(true)
//                    ->setLeftOrRight('right');
//
//
//                $na->setQuestion($nq);
//
//                $quiz->addQuestion($nq);
//
//                $em->persist($quiz);
//
//                $em->flush();
//
//
//
//                return new Response('ok');


            } else {
                // not a teacher trying to edit a quiz, we cannot allow that

                return new Response("You can't do that.");
            }
        }


    }


    private function getQuizEntity($json) {

    }
}