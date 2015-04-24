<?php


namespace Quiz\CoreBundle\Controller;


use Doctrine\ORM\PersistentCollection;
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


        $user = $this->getUser();

        $router = $this->get('router');


        $em = $this->get('doctrine.orm.entity_manager');


        $userManger = $this->get('fos_user.user_manager');



        if (!isset($user)) {
            // we don't have a logged in user, redirect user to login page
            return new RedirectResponse($router->generate('fos_user_security_login'), 301);
        }

        $userRoles = $user->getRoles();


        if (in_array('ROLE_TEACHER', $userRoles)) {
            // this is a teacher, go to teacher dashboard

            $message = 'Καθηγητής';

            return $this->render('@QuizCore/Teacher/teacherDashboard.html.twig', ['user' => $this->getUser(), 'message' => $message]);

        } else if (in_array('ROLE_SUPER_ADMIN',$userRoles)) {
            // super
        }

        return new Response(implode('' ,  $userRoles));
    }


    public function ProfileAction()
    {


    }

}