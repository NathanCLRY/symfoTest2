<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{   
    #[Route('/client', name: 'app_client')]
    public function index(ClientRepository $repository): Response
    {
        $clients = $repository->findAll();

        return $this->render('client/client.html.twig', [
            'clients' => $clients
        ]);
    }

    #[Route('client/nouveau', 'client.new', methods: ['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $client = $form->getData();
            $manager->persist($client);
            $manager->flush();
            return $this->redirectToRoute('app_client');
            $this->addFlash(
                'success',
                'Votre ingrédient a été ajouté avec succès !'
            );
        }
        return $this->render('client/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('client/edit/{id}','client.edit', methods: ['GET','POST'])]
    public function edit(ClientRepository $repository,
    int $id,
    Request $request,
    EntityManagerInterface $manager): Response
    {
        $client = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $client = $form->getData();
            $manager->persist($client);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès !'
            );
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }


    #[Route('client/sup/{id}', 'client.sup' , methods:['GET'])]
    public function delete(ClientRepository $repository,
    int $id,
    EntityManagerInterface $manager): Response
    {
        $client = $repository->findOneBy(["id" => $id]);
        $manager->remove($client);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_client');
    }
}
