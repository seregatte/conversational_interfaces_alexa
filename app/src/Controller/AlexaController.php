<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AlexaController extends Controller
{
    /**
     * @Route("/", name="alexa")
     */
    public function index()
    {
        return new Response("From Alexa Controller Index");
    }
}
