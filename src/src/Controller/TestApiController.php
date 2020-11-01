<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestApiController extends AbstractController
{
   /**
     * @Route("/api/users", methods={"GET"})
     */
    public function listAction(Request $request): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        
        $usersArray = [];
        foreach($users as $user) {
            $usersArray[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'annivarsaryDate' => $user->getAnniversaryDate()
            ];
        }

        return $this->json($usersArray);
    }

    /**
     * @Route("/api/users", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {   
        $name = $request->request->get('name');
        $anniversaryDate = $request->request->get('anniversary_date');
        
        if ($name === null || $anniversaryDate === null) {
            return new Response(
                'Missing "name" (string)  or "anniversary_date" (YYYY-MM-DD) params ',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );    
        }
        
        $newUser = new User();
        $newUser->setName($name);
        $newUser->setAnniversaryDate(new \DateTime($anniversaryDate));

        $em = $this->getDoctrine()->getManager();
        $em->persist($newUser);
        $em->flush();

        return new Response('User inserted!', Response::HTTP_OK);
    }

    /**
     * @Route("/api/users" , methods={"PUT"})
     */
    public function update(Request $request): Response
    {
        $name = $request->request->get("name");
        $anniversaryDate = $request->request->get("anniversary_date");
        $id = $request->request->get("id");

        if ($name === null || $anniversaryDate === null || $id === null) {
            return new Response(
                'Missing "id" (int) "name" (string)  or "anniversary_date" (YYYY-MM-DD) params ',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if ($user === null) {
            return new Response(
                'User with id ' . $id . ' doesn\'t exist.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $user->setName($name);
        $user->setAnniversaryDate(new \DateTime($anniversaryDate));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('User updated!', Response::HTTP_OK);
    }

    /**
     * @Route("/api/users", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->request->get("id");
        
        if ($id === null) {
            return new Response(
                'Missing "id" (int) param.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if ($user === null) {
            return new Response(
                'User with id ' . $id . ' doesn\'t exist.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new Response('User removed!', Response::HTTP_OK);
    }
}
