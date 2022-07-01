<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User1;
use App\Form\AddressType;
use App\Form\ChangepassType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }
    #[Route('/account/password', name: 'modif_pass')]
    public function pass(Request $request,UserPasswordHasherInterface $hasher): Response
    {
        $notification = null;
        $user=$this->getUser();
        $form=$this->createForm(ChangepassType::class, $user);
        $Manager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $oldpass= $form->get('old_password')->getData();

            if ($hasher->isPasswordValid($user, $oldpass)){

                $newpass=$form->get('new_password')->getData();
                $password=$hasher->hashPassword($user,$newpass);
                $user->setPassword($password);


                $Manager->flush();
                $notification= "votre mot de passe a bien été modifié";
            }
            else{
                $notification="tappe ton mot de passe actuelle svp";
            }
        }

        return $this->render('account/password.html.twig',[
            'form' =>$form->createView(),
            'notification'=>$notification
        ]);
    }
    #[Route('/account/address', name: 'adresse')]
    public function adresse(Request $request){

        return $this->render('account/address.html.twig');
    }
    #[Route('/account/ajout_address', name: 'add_adresse')]
    public function addadresse(Request $request){
        $adresse=new Address();
        $form=$this->createForm(AddressType::class,$adresse);

        $form->handleRequest($request);
        $Manager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()&& $form->isValid() ){
            $adresse->setUser($this->getUser());
            $Manager->persist($adresse);
            $Manager->flush();
            return $this->redirectToRoute('adresse');


        }

        return $this->render('account/address_ajout.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    #[Route('/account/modif_address/{id}', name: 'upp_adresse')]
    public function updateadresse(Request $request,$id){
        $adresse=$this->getDoctrine()->getRepository(Address::class)->findOneById($id);
        if (!$adresse || $adresse->getUser() != $this->getUser()){
            return $this->redirectToRoute('adresse');
        }
        $form=$this->createForm(AddressType::class,$adresse);

        $form->handleRequest($request);
        $Manager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()&& $form->isValid() ){

            $Manager->flush();
            return $this->redirectToRoute('adresse');


        }

        return $this->render('account/address_ajout.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    #[Route('/account/deleate_address/{id}', name: 'del_adresse')]
    public function deladresse($id){
        $adresse=$this->getDoctrine()->getRepository(Address::class)->findOneById($id);
        if ($adresse || $adresse->getUser() == $this->getUser()){
            $manager=$this->getDoctrine()->getManager();
            $manager->remove($adresse);
            $manager->flush();

        }
        $form=$this->createForm(AddressType::class,$adresse);


        return $this->redirectToRoute('adresse');
    }
}
