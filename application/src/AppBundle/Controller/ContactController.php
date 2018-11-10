<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class ContactController extends BaseController
{

    /**
     * add contact us
     * @ApiDoc(
     *  description="send contact us form to the backenddfsdfsdfsfsd",
     *  tags={
     *      "testing"
     *  },
     *  methods="POST",
     *  section="ContactUs",
     *  parameters={
     *      {"name"="email", "dataType"="Email", "required"=true},
     *      {"name"="message", "dataType"="String", "required"=true}
     *  },
     *  statusCodes={
     *      200="Returned on success",
     *      401="Unauthorized",
     *      404="Not Found"
     *  }
     * )
     * @author Micheal Mounir <micheal.mouner@gmail.com>
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/api/contact",methods={"POST"})
     * @Method("POST")
     */
    public function addAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact, array("method" => $request->getMethod()));
        $this->processForm($request, $form);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->createSuccessfulApiResponse($contact);
        }
        $this->throwApiProblemValidationException($form, 400);
    }

}
