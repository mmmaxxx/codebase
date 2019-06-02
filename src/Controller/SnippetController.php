<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Snippet;
use App\Form\SnippetForm;

/**
 * Snippet controller
 * @Route("/api", name="api_")
 */
class SnippetController extends AbstractFOSRestController {

    /**
     * Lists all Snippets
     * @Rest\Get("/snippets")
     *
     * @return Response
     */
    public function getSnippetAction() {
        $repository = $this->getDoctrine()->getRepository(Snippet::class);
        $snippets = $repository->findAll();
        return $this->handleView($this->view($snippets));
    }

    /**
     * Create a snippet
     * @Rest\Post("/snippet")
     *
     * @param Request $request
     * @return Response
     */
    public function postSnippetAction(Request $request) {

        $snippet = new Snippet();
        $form = $this->createForm(SnippetForm::class, $snippet);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($snippet);
            $em->flush();
            return $this->handleView($this->view(['created' => true], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));

    }

}
