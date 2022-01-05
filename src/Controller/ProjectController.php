<?php
//Refaire le projet
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\TaskRepository;

class ProjectController extends AbstractController{
    /**
     * @Route("/project", name="project")
     */
    public function index(Request $request, ManagerRegistry $doctrine, ProjectRepository $projectRepository): Response{
        $projects= $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    /**
     * @Route("/project/add", methods={"GET"}, name="project_add")
     */
    public function addProject(UserRepository $userRepository): Response{
        $users=$userRepository->findAll();
        return $this->render("project/AddProject.html.twig", ["users"=>$users]);
    }

     /**
      * @Route("/project/save", methods={"post"}, name="project_add_save")
      */
     public function saveProject(Request $request,  ManagerRegistry $doctrine){
          $projectData= new Project;
          $entityManager= $doctrine->getManager();
          $projectData->setName($request->request->get("projectName"));
          $projectData->setDescription($request->request->get("description"));
          $projectData->setStartDate(new\DateTime($request->request->get("startDate")));
          $projectData->setEndDate(new\DateTime($request->request->get("endDate"))) ;
          $entityManager->persist($projectData); //Enregistrement des données
          $entityManager->flush();
          return $this->redirectToRoute("project");
     }
     /**
      * @Route("/project/edit/{id}", methods={"GET"}, name="project_edit")
      */
      public function editProject(ManagerRegistry $doctrine, Project $project){
          return $this->render("project/editProject.html.twig" , ["project"=>$project]);
      }

   /**
    * @Route("/project/edit/save/{id}", methods={"POST"}, name="project_edit_save")
    */
    public function editProjectSave(Request $request, ManagerRegistry $doctrine, Project $project){
        $entityManager= $doctrine->getManager();
        $project->setName($request->request->get("projectName"));
        $project->setDescription($request->request->get("description"));
        $project->setStartDate(new\DateTime($request->request->get("startDate")));
        $project->setEndDate(new\DateTime($request->request->get("endDate"))) ;
        $entityManager->persist($project); //Enregistrement des données
        $entityManager->flush();
        return $this->redirectToRoute("project");
    }

    /**
     * @Route("/project/delete/{id}",methods={"POST"} ,name="project_delete")
     */

     public function deleteProject(ManagerRegistry $doctrine, Project $project) :Response{
        $entityManager= $doctrine->getManager();
        $entityManager->remove($project);
        $entityManager->flush();
        return $this->redirectToRoute("project");
     }

    /**
     * @Route("/project/edit/{id}/addUsers", methods={"GET"}, name="project_add_users")
     */
     public function addUsersEdit(ManagerRegistry $doctrine, Request $request, Project $project, UserRepository $userRepository/*, LoggerInterface $logger*/){
        $users= $userRepository->findAll();
        $projectUsers= $project->getUsers();
        return $this->render("project/editUsers.html.twig" , ["users"=>$users, "project"=>$project, "projectUsers"=>$projectUsers]);
    }

    /**
    * @Route("/project/edit/{id}/addUsers/save", methods={"POST"}, name="project_add_users_save")
    */

    public function addUsersEditSave(ManagerRegistry $doctrine, Request $request, Project $project, UserRepository $userRepository/*, LoggerInterface $logger*/){

        $entityManager= $doctrine->getManager();
        $userIdList= $request->request->get("userInput",[]);
        //$logger->debug("valeur userId",["userId"->$userIdList]);
        foreach ($userIdList as $userId) {
            $user= $userRepository->find($userId);
            $project->addUser($user);
        }
        $entityManager->persist($project);
        $entityManager->flush();
        return $this->redirectToRoute("project");
    }
    /**
    * @Route("/project/{id}", methods={"GET"}, name="project_manager")
    */
    public function projectManager(ManagerRegistry $doctrine, Request $request, Project $project, UserRepository $userRepository, TaskRepository $taskRepository){
        $users= $userRepository->findAll();
        $tasks= $taskRepository->findAll();
        $projectTasks= $project->getTasks();
        $projectUsers= $project->getUsers();
        return $this->render("project/projectManager.html.twig" , ["project"=>$project, "projectUsers"=>$projectUsers, "projectTasks"=>$projectTasks]);
    }
}

/*public function clearUsers(): self{
    foreach ($this->users as $user) {
        $this->removeUser($user)
    }
}*/
