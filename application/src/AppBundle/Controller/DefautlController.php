<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class DefautlController extends BaseController
{

    /**
     * home page
     * @Route("/",methods={"GET"})
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->redirect("/api/doc");
    }

}
