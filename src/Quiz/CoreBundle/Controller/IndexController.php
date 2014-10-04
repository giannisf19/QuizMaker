<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 4/10/2014
 * Time: 22:45
 */



use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {

        return $this->render('QuizCoreBundle:Index:index.twig');
    }
}