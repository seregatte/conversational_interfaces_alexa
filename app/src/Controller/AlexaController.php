<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Confession;

use Alexa\Response\Response as AlexaResponse;
use Alexa\Request\Request as AlexaRequest;
use Alexa\Request\IntentRequest;
use Alexa\Request\Certificate;

class AlexaController extends Controller
{
    private $_alexaId = 'amzn1.ask.skill.a61f06c9-e0b4-4419-8e5e-14acbafaa38d';

    /**
     * @Route("/alexa", name="alexa")
     */
    public function index(Request $request)
    {
        $content = $request->getContent();

        if (!empty($content)) {

             // Drupal caching of a downloaded certificate.
            $certificate = new Certificate(
                $request->headers->get('signaturecertchainurl'),
                $request->headers->get('signature')
            );

            $alexa = new AlexaRequest($content, $this->_alexaId);

            $alexa->setCertificateDependency($certificate);

            $alexaRequest = $alexa->fromData();

            $repository = $this->getDoctrine()->getRepository(Confession::class);

            $response = new AlexaResponse();

            switch ($alexaRequest->intentName) {
                case 'HeardAConfession':
                    $confession = $repository->getARandomConfession();
                    $response->endSession()->respond($confession->getBody());
                    // $response->endSession()->respondSSML($confession->getBody());
                    break;
                case 'MakeAConfession':
                    $slot = $alexaRequest->getSlot('Confession');
                    $repository->registerAConfession($slot);
                    $response->endSession()->respond("I got it, you are so bad!");
                    break;
                default:
                    $response->respond('I can heard and tell confessions, in order to make a confession you can say... I wanna say that... and what you would like to confession. Or you can say "I wanna heard a confession"');
                    break;
            }

            return new JsonResponse($response->render());

        }
        return new JsonResponse(NULL, 500);
    }
}
