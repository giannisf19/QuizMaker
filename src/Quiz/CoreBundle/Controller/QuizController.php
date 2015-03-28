<?php

namespace Quiz\CoreBundle\Controller;

use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Quiz\CoreBundle\Entity\Result;
use Quiz\CoreBundle\Entity\TestResult;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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

            $em = $this->getDoctrine()->getManager();
            $quiz = $em->getRepository('QuizCoreBundle:Quiz')->find($id);


            // check if the quiz is public OR we have a user logged in

            if ($quiz->getIsPrivate() && !$user) {
                return new RedirectResponse($this->get('router')->getContext()->getBaseUrl(), 301);
            }


            // render the page
           return  $this->render('@QuizCore/Default/quiz.html.twig', ['quiz' => $quiz]);

        }
    }
}