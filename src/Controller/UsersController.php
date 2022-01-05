<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Entity\User;




class UsersController extends AbstractController{
    /**
     * @Route("/users", name="users")
     */
    public function index(Request $request, UserRepository $userRepository, ManagerRegistry $doctrine): Response{
        $users= $userRepository->findAll();
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/users/add/",methods={"GET"} ,name="users_add")
     */
    /*Faire une fonction get pour l'affichage et changer la route de  l'url pour la fonction post*/
    public function addUsers(Request $request): Response{
        return $this->render("users/addUsers.html.twig");
    }

    /**
     * @Route("/users/save",methods={"POST"} ,name="users_save")
     */

    public function saveUsers(Request $request,  ManagerRegistry $doctrine): Response{
        $userData= new User;
        $entityManager= $doctrine->getManager();
        $userData->setFirstName($request->request->get("firstName"));
        $userData->setLastName($request->request->get("lastName"));
        $userData->setEmail($request->request->get("email"));
        $entityManager->persist($userData); //Enregistrement des données
        $entityManager->flush();
        return $this->redirectToRoute("users");
    }

    /**
     * @Route("/users/edit/{id}", methods={"GET"}, name="users_edit")
     */
    public function editUsers(ManagerRegistry $doctrine, User $user): Response{
        return $this->render("users/editUsers.html.twig", ["utilisateur"=>$user]);
    }

    /**
     * @Route("/users/edit/save/{id}", methods={"POST"}, name="users_edit_save")
     */

    public function editUsersSave(Request $request, ManagerRegistry $doctrine, User $user): Response{
        $entityManager= $doctrine->getManager();
        $user->setFirstName($request->request->get("firstName"));
        $user->setLastName($request->request->get("lastName"));
        $user->setEmail($request->request->get("email"));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute("users");
    }

    /**
     * @Route("/users/delete/{id}",methods={"POST"} ,name="users_delete")
     */

     public function deleteUser(ManagerRegistry $doctrine, User $user){
        $entityManager= $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute("users");
     }
}
