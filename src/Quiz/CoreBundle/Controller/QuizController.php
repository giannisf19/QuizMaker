<?php

namespace Quiz\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    public function indexAction($id)
    {


       if ($id == 0) {
           return new RedirectResponse($this->get('router')->getContext()->getBaseUrl(), 302);
       }

        else {

            // Valid test

           return  $this->render('quiz.html.twig');

        }
    }
}