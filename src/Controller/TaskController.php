<?php
//Refaire le projet
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Entity\Task;

class TaskController extends AbstractController{
        /**
        * @Route("/project/{id}/task", methods={"GET"}, name="task")
        */
        public function addTask(Project $project): Response{
            $projectTasks= $project->getTasks();
             return $this->render("task/addTask.html.twig",["project"=>$project, "projectTasks"=>$projectTasks]);
        }

        /**
        * @Route("/project/{id}/task/save", methods={"post"}, name="task_add_save")
        */
        public function saveTask(Request $request,  ManagerRegistry $doctrine,  ValidatorInterface $validator, SessionInterface $session, Project $project/*On ajoute projet dans l'url pour récuperer l'id du projet dans les paramettre de l'url*/){
            $taskData= new Task;
            $entityManager= $doctrine->getManager();
            $taskData->setName($request->request->get("taskName"));
            $taskData->setDescription($request->request->get("taskDescription"));
            $taskData->setStartDateStr($request->request->get("taskStartingDate"));
            $taskData->setStartDate(new\DateTime($request->request->get("taskStartingDate")));
            $taskData->setEndDateStr($request->request->get("taskEndingDate"));
            $taskData->setEndDate(new\DateTime($request->request->get("taskEndingDate")));
            $taskData->setProject($project); //recupération de l'id du projet
            $errors=$validator->validate($taskData);
            if (count($errors)>0){
                $this->addFlash(
                    "error",
                    $errors
                );
                return $this->redirectToRoute("task",["id"=>$project->getId()]);
            }
            $entityManager->persist($taskData); //Enregistrement des données
            $entityManager->flush();
            return $this->redirectToRoute("project_manager",["id"=>$project->getId()]);
        }
        /**
         * @Route("/project/{projectId}/task/{taskId}/edit", methods={"GET"}, name="task_edit")
         * @ParamConverter("project", options={"mapping": {"projectId" : "id"}})
         * @ParamConverter("task", options={"mapping": {"taskId"   : "id"}})
         */

         public function editTask(Request $request,  ManagerRegistry $doctrine, Project $project, Task $task){
            return $this->render("task/editTask.html.twig" , ["task"=>$task, "project"=>$project]);
         }
        /**
        * @Route("/project/{projectId}/task/{taskId}/edit/save", methods={"POST"}, name="task_edit_save")
        * @ParamConverter("project", options={"mapping": {"projectId" : "id"}})
        * @ParamConverter("task", options={"mapping": {"taskId"   : "id"}})
        */

        public function editTaskSave(Request $request,ValidatorInterface $validator, SessionInterface $session, ManagerRegistry $doctrine, Project $project, Task $task){
            $entityManager= $doctrine->getManager();
            $task->setName($request->request->get("taskName"));
            $task->setDescription($request->request->get("taskDescription"));
            $task->setStartDateStr($request->request->get("taskStartingDate"));
            $task->setStartDate(new\DateTime($request->request->get("taskStartingDate")));
            $task->setEndDateStr($request->request->get("taskEndingDate"));
            $task->setEndDate(new\DateTime($request->request->get("taskEndingDate")));
            $task->setProject($project);
                $errors=$validator->validate($task);
                if (count($errors)>0){
                    $this->addFlash(
                        "error",
                        $errors
                    );
                    return $this->redirectToRoute("task_edit",["projectId"=>$project->getId(),"taskId"=>$task->getId()]);
                }
            $entityManager->persist($task);
            $entityManager->flush();
            return $this-> redirectToRoute("project");
        }

        /**
        * @Route("/project/{projectId}/task/{taskId}/delete", methods={"POST"}, name="task_delete")
        * @ParamConverter("project", options={"mapping": {"projectId" : "id"}})
        * @ParamConverter("task", options={"mapping": {"taskId"   : "id"}})
        */

        public function deleteTask(Request $request, ManagerRegistry $doctrine, Project $project, Task $task){
            $entityManager= $doctrine->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
            return $this->redirectToRoute("project_manager",["id"=>$project->getId()]);
        }

}
