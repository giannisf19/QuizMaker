<?php

namespace Quiz\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function emailAvailableAction(Request $request) {
        $email = $request->request->get('email');
        $d = $this->get('doctrine')->getRepository('QuizCoreBundle:UserEntity');
        $user =   $d->findBy(['email' => $email]);


        return count($user) >=  1 ?  new Response(0) : new Response(1);
    }


}