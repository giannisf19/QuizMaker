<?php

namespace Quiz\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use Proxies\__CG__\Quiz\CoreBundle\Entity\UserEntity;
use Quiz\CoreBundle\Engine\QuizInfo;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\MediaElement;
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

        $serializer = $this->get('serializer');

        if (! isset($user)) {
            return new RedirectResponse($this->get('router')->generate('fos_user_security_login'));
        }


       $hisQuiz = false;
       $isTeacher = in_array('ROLE_TEACHER', $user->getRoles());
        $message = 'Καθηγητής';


        if ($isTeacher && $id == 0) {
            // create new quiz

            $action = 'Δημιουργία';
            return $this->render('@QuizCore/Teacher/editQuiz.html.twig', ['quiz' => '{}', 'message' => $message, 'action' => $action ]);
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

            return $this->render('@QuizCore/Teacher/editQuiz.html.twig', ['quiz' => $serializer->serialize($quiz, 'json'),
                'message' => $message, 'action' => $action, 'name' => $quiz->getName()]);

        } else {
            return new Response("error");
        }



    }

    public function getMediaAction()
    {
        $serializer = $this->get('serializer');
        $media = $this->get('doctrine.orm.entity_manager')->getRepository('QuizCoreBundle:MediaElement')->findAll();

        return new Response($serializer->serialize($media, 'json'));
    }

    public function getQuestionsAction()
    {


        /* @var $user \Quiz\CoreBundle\Entity\UserEntity */
        $user = $this->getUser();
        $roles = $user->getRoles();
        $serializer = $this->get('serializer');



        if (isset($user)) {

            if (in_array('ROLE_TEACHER', $roles)) {

                /* @var $quizes ArrayCollection<Quiz> */
                $quizes = $user->getQuizes();

                $questions = [];

                foreach($quizes as $quiz) {
                    /* @var $quiz Quiz */
                    foreach($quiz->getQuestions() as $qq) {
                        $questions[] = $qq;
                    }
                }






                return new Response($serializer->serialize($questions, 'json'));

            } else {
                new Response('You are not a teacher');
            }
        } else {
            return new Response('Access Denied.');
        }

    }

    public function getQuizResultsAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $quiz = $em->find('QuizCoreBundle:Quiz', $request->getContent());
       $serializer = $this->get('serializer');


        if ($this->getUser()->getQuizes()->contains($quiz)) {
            $results = $quiz->getTestResults();
            return new Response($serializer->serialize($results, 'json', SerializationContext::create()->setGroups(['results'])));
        } else {
            return new Response("[]");
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

                $quizId = null;

                if (isset($quiz)) {
                    // existing quiz
                    $this->saveDataToDb($quiz, $jsonObject);
                    $quizId = $quiz->getId();

                } else {
                    // create new quiz

                    $newQuiz = new Quiz();

                    $newQuiz->setOwners($user);
                    $em->persist($newQuiz);
                    $this->saveDataToDb($newQuiz, $jsonObject);

                    $em->flush();
                    $quizId = $newQuiz->getId();

                }


                $em->flush();
                return new Response($quizId);


            } else {
                // not a teacher trying to edit a quiz, we cannot allow that

                return new Response("You can't do that.");
            }
        }


    }


    /**
     * @param $quiz Quiz
     * @param $jsonObject
     */
    private function saveDataToDb($quiz, $jsonObject) {


        /* @var $quiz Quiz */

        $quiz->setName($jsonObject->name);
        $quiz->setIsDisabled($jsonObject->is_disabled);
        $quiz->setIsPrivate($jsonObject->is_private);
        $quiz->setTime($jsonObject->time);
        $quiz->setPassGrade($jsonObject->pass_grade);
        $quiz->setTotalGrade($jsonObject->total_grade);
        $quiz->setHasNegativeGrade($jsonObject->has_negative_grade);
        $quiz->setShowQuestionsRandomly($jsonObject->show_questions_randomly);

        $qids = array_map(function($item){
            return $item->id;
        }, $jsonObject->questions);


        foreach ($quiz->getQuestions() as $qqqq) {
            /* @var $qqqq Question */


            if (!in_array($qqqq->getId(), $qids) ) {
                $qqqq->removeQuize($quiz);
            }
        }



        foreach($jsonObject->questions as $question) {
            /* @var $question object */

            $currentQId=  $question->id;

            $currentQuestionsFromDb = null;



            foreach ($quiz->getQuestions() as $qq) {
                /* @var $qq Question */
                if ($qq->getId() == $question->id) {
                    $currentQuestionsFromDb = $qq;
                }
            }

            if ($currentQuestionsFromDb == null) {
                $currentQuestionsFromDb = $this->get('doctrine.orm.entity_manager')->find('QuizCoreBundle:Question', $question->id);
            }

            if ($question->id == 0) {
                // new question to insert
                $questionToInsert = new Question();
                $questionToInsert->setOrder($question->order);
                $questionToInsert->setType($question->type);
                $questionToInsert->setQuestionText($question->question_text);
                $questionToInsert->setCorrectAnswerGrade($question->correct_answer_grade);
                $questionToInsert->setWrongAnswerGrade($question->wrong_answer_grade);

                $quiz->addQuestion($questionToInsert);
                $currentQuestionsFromDb = $questionToInsert;

            }

            else {

                $currentQuestionsFromDb->setQuestionText($question->question_text);
                $currentQuestionsFromDb->setType($question->type);
                $currentQuestionsFromDb->setOrder($question->order);
                $currentQuestionsFromDb->setCorrectAnswerGrade($question->correct_answer_grade);
                $currentQuestionsFromDb->setWrongAnswerGrade($question->wrong_answer_grade);

                if (isset($question->selected)) {
                    $quiz->addQuestion($currentQuestionsFromDb);
                }

            }


            $aids  = array_map(function($item){
                return $item->id;
            }, $question->answers);


            $mids = array_map(function($item){
                return $item->id;
            }, $question->media_elements);






            foreach($currentQuestionsFromDb->getMediaElements() as $todel) {
                /* @var $todel MediaElement */

                if (! in_array($todel->getId(), $mids)) {
                    $todel->removeQuestion($currentQuestionsFromDb);
                }
            }

            foreach($question->media_elements as $mediaElement) {

                $currentMediaElementFromDb = null;

                foreach ($currentQuestionsFromDb->getMediaElements() as $mm) {
                    /* @var $mm MediaElement */


                    if ($mm->getId() == $mediaElement->id) {
                        $currentMediaElementFromDb = $mm;
                    }
                }



                if ($mediaElement->id == 0) {
                    // create new
                    $mt = new MediaElement();

                    $mt->setMediaType($mediaElement->media_type);
                    $mt->setSrc($mediaElement->src);
                    $currentQuestionsFromDb->addMediaElement($mt);


                } else {
                    if ($currentMediaElementFromDb == null) {
                        $currentMediaElementFromDb = $this->get('doctrine.orm.entity_manager')->find('QuizCoreBundle:MediaElement', $mediaElement->id);
                        $currentQuestionsFromDb->addMediaElement($currentMediaElementFromDb);
                    }
                }

            }


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
                    $answerToAdd->setFeedback($answer->feedback);

                    $answerToAdd->setQuestion($currentQuestionsFromDb);


                } else {
                    //edit
                    $currentAnswerFromDb->setAnswerText($answer->answer_text);
                    $currentAnswerFromDb->setLeftOrRight($answer->left_or_right);
                    $currentAnswerFromDb->setIsCorrect($answer->is_correct);
                    $currentAnswerFromDb->setFeedback($answer->feedback);
                }
            }

        }

    }
}