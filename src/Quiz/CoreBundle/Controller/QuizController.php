<?php

namespace Quiz\CoreBundle\Controller;

use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class QuizController extends Controller
{
    public function indexAction($id)
    {

        // No quiz selected go back to main page
        // or show error page

       if ($id == 0) {
           return new RedirectResponse($this->get('router')->getContext()->getBaseUrl(), 302);
       }

        else {

            // Valid test
            // render the test page


            $em = $this->getDoctrine()->getManager();

            $quiz = $em->getRepository('QuizCoreBundle:Quiz')->find(1);


           return  $this->render('@QuizCore/Default/quiz.html.twig', ['quiz' => $quiz]);

        }
    }
}