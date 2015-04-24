<?php

namespace Quiz\CoreBundle\Controller;

use Quiz\CoreBundle\Engine\QuizInfo;
use Quiz\CoreBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function editOrCreateQuizAction($id) {

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


            $action = 'Δημιουργία';
            return $this->render('@QuizCore/Teacher/editQuiz.html.twig', ['quiz' => null, 'message' => $message, 'action' => $action ]);
        }

        foreach ($user->getQuizes() as $quiz) {
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

    public function saveQuizAction()
    {

        $user = $this->getUser();
        $serializer = $this->get('serializer');
        $em = $this->get('doctrine.orm.entity_manager');


        if (!isset($user)) {
            return new Response('Access Denied');
        } else {
            $roles = $user->getRoles();

            if (in_array('ROLE_TEACHER', $roles)) {
                // The user is a teacher, he can edit a quiz


                $stringJson = $this->get('request')->getContent();
                $toSave  = json_decode($stringJson);

                /* @var $s Quiz */
                $s =  $serializer->deserialize($toSave, 'Quiz\CoreBundle\Entity\Quiz', 'json');


//                $em->persist($s);
//                $em->flush();

                return new Response($toSave->id);

            } else {
                // not a teacher trying to edit a quiz, we cannot allow that

            }
        }


    }


    private function getQuizEntity($json) {

    }
}