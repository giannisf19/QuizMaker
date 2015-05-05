<?php


namespace Quiz\CoreBundle\Controller;


use Doctrine\ORM\PersistentCollection;
use FOS\UserBundle\Model\User;
use JMS\Serializer\SerializationContext;
use Quiz\CoreBundle\Engine\QuizEngine\QuizTest;
use Quiz\CoreBundle\Entity\Answer;
use Quiz\CoreBundle\Entity\OngoingTest;
use Quiz\CoreBundle\Entity\Question;
use Quiz\CoreBundle\Entity\Quiz;
use Quiz\CoreBundle\Entity\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Quiz\CoreBundle\Engine\QuizInfo;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    public function indexAction()
    {



        /* @var $user UserEntity  */
        $user = $this->getUser();
        $router = $this->get('router');
        $em = $this->get('doctrine.orm.entity_manager');
        $serializer = $this->get('serializer');

        $all = $em->getRepository('QuizCoreBundle:Quiz')->findAll();

        $retValues = [];

        foreach($all as $quiz) {
            /* @var $quiz Quiz */

            if ($user->getQuizes()->contains($quiz)) {
                $ret  = new \stdClass();
                $ret->name = $quiz->getName();
                $ret->id = $quiz->getId();

                array_push($retValues, $ret);
            }

        }

        $userManger = $this->get('fos_user.user_manager');




        if (!isset($user)) {
            // we don't have a logged in user, redirect user to login page
            return new RedirectResponse($router->generate('fos_user_security_login'), 301);
        }

        $userRoles = $user->getRoles();


        if (in_array('ROLE_TEACHER', $userRoles)) {
            // this is a teacher, go to teacher dashboard

            $message = 'Καθηγητής';


            return $this->render('@QuizCore/Teacher/teacherDashboard.html.twig', ['quiz' => json_encode($retValues)  ,'user' => $this->getUser(), 'message' => $message]);

        } else if (in_array('ROLE_SUPER_ADMIN',$userRoles)) {

            return $this->render('index.html.twig', ['message' => 'Διαχειριστής']);
        }



        // student
        $enabledQuizes = $em->getRepository('QuizCoreBundle:Quiz')->findBy(['isDisabled' => false]);
        $enabledQuizes = array_filter($enabledQuizes, function($item) {
            /* @var $item Quiz */
            return $item->getQuestions()->count() > 0;
        });
        return $this->render('@QuizCore/Default/index.html.twig', ['message' => 'Αρχική σελίδα', 'quizes' => $enabledQuizes,
            'sq' => $serializer->serialize($enabledQuizes, 'json', SerializationContext::create()->setGroups(['public']))]);
    }


    public function ProfileAction()
    {


    }

    public function historyAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $resultsByUser  = $em->getRepository('QuizCoreBundle:TestResult')->findBy(['user' => $this->getUser()]);

        return $this->render('@QuizCore/Quiz/history.html.twig', ['results' =>  $resultsByUser, 'message' => 'Ιστορικό']);

    }

}