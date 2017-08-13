<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use AppBundle\Service\MysteryNumberService;

class MysteryNumberController extends Controller
{
    /**
     * @Route("/api/mystery", name="mystery")
     * @Method({"GET"})
     */
    public function mysteryNumberAction(Request $request, MysteryNumberService $mysteryNumberService)
    {
        $trustedHttpReferers = $this->getParameter('trusted_client_referers');
        $httpReferer = $request->headers->get('referer');
        if (!in_array($httpReferer, $trustedHttpReferers)) {
            throw new AccessDeniedHttpException("Only trusted clients are allowed to use this API.");
        }

        $guess = $request->query->getInt('guess', -1);

        if ($guess < 0 || $guess > 100) {
            throw new BadRequestHttpException("Parameter 'guess' is mandatory and must be an integer betwen 0 and 100.");
        }

        $result = $mysteryNumberService->play(intval($guess, 10));

        return $this->json([ 'success' => true, 'data' => $result ]);
    }
}
