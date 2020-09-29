<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @param PinRepository $pinRepository
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function index(PinRepository $pinRepository)
    {
        return $this->render('pins/index.html.twig', [
            'pins' => $pinRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/pin/{id<[0-9]+>}", name="pin_show", methods={"GET"})
     * @param PinRepository $pinRepository
     * @param $id
     * @return Response
     */
    public function show(PinRepository $pinRepository, $id)
    {
        $pin = $pinRepository->find($id);

        return $this->render('pins/show.html.twig', [
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pin/create", name="pin_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PinType::class, new Pin());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $pin = $form->getData();
            $pin->setUser($this->getUser());

            $em->persist($pin);
            $em->flush();
            
            $this->addFlash('success', 'Pin Successfuly Created');

            return $this->redirectToRoute("home");
        }

        return $this->render(
            'pins/create.html.twig', ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/pin/{id<[0-9]+>}/edit", name="pin_edit", methods={"GET", "PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PinRepository $pinRepository
     * @param $id
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $em, PinRepository $pinRepository, $id)
    {
        $pin = $pinRepository->find($id);
        $user = $this->getUser();

        if($pin->getUser() == $user || $user->isAdmin()) {

            $form = $this->createForm(PinType::class, $pin, ['method' => 'PUT']);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Pin Successfuly Updated');

                return $this->redirectToRoute("home");
            }

            return $this->render('pins/edit.html.twig', [
                'pin' => $pin,
                'form' => $form->createView()
            ]);
        }

        $this->addFlash('success', 'You are not of owner of this pin');
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/pin/{id<[0-9]+>}/delete", name="pin_delete", methods={"DELETE"})
     * @param PinRepository $pinRepository
     * @param $id
     */
    public function delete(Request $request, PinRepository $pinRepository, EntityManagerInterface $em, $id)
    {
        $pin = $pinRepository->find($id);
        $user = $this->getUser();

        if($pin->getUser() == $user || $user->isAdmin()) {
            if($this->isCsrfTokenValid('pin' . $id, $request->request->get("csrf_token"))) {
                $em->remove($pin);
                $em->flush();
            }
            $this->addFlash('info', 'Pin Successfuly Deleted');
        } else {
            $this->addFlash('success', 'You are not of owner of this pin');
        }

        return $this->redirectToRoute('home');
    }
}
