<?php

namespace Quiz\CoreBundle\Controller;

use Doctrine\DBAL\Types\JsonArrayType;
use JMS\Serializer\SerializationContext;
use Quiz\CoreBundle\Entity\Answer;
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


            // render the page
           return  $this->render('@QuizCore/Quiz/quiz.html.twig', ['quiz' => $quiz, 'types' => $t,
               'time' => $time
           ]);

        }
    }


    public function startAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');
        $quizrep  = $em->getRepository('QuizCoreBundle:Quiz');
        $serializer = $this->get('serializer');
        $quiz = $quizrep->findOneBy(['id' => 1]);

        return $this->render('@QuizCore/Quiz/quizStart.html.twig', ['quiz' => $quiz, 'sq' => $serializer->serialize($quiz, 'json'
        , SerializationContext::create()->setGroups(array('public'))), 'message' => 'Εξέταση']);
    }



    public function submitQuizAction(Request $request) {
        $response = $request->getContent();

        $serializer = $this->get('serializer');


        return new JsonResponse($response);
    }
}