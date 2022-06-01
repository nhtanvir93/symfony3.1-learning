<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class UserController extends Controller
{
    private $entityManager;
    private $user;
    private $userRepository;
    protected $container;

    public function __construct(ContainerInterface $container, User $user)
    {
        $this->setContainer($container);

        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $this->user = $user;
        $this->userRepository = $this->entityManager->getRepository('AppBundle:User');
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function indexAction(Request $request) {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/users/create", name="create_user", methods={"GET","POST"})
     */
    public function createAction(Request $request) {
        $this->user->setCreatedAt(
            new \DateTime(
                'now',
                new \DateTimeZone('Asia/Dhaka')
            )
        );

        $form = $this->createForm(UserType::class, $this->user, [
            'action' => $this->generateUrl('create_user'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($this->user);
            $this->entityManager->flush();

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add('success', 'New user is created successfully !!!');

            return $this->redirectToRoute('show_user', [
                'id' => $this->user->getId()
            ]);
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('create_user')
        ]);
    }

    /**
     * @Route("/users/{id}", name="show_user")
     */
    public function showAction($id) {
        return $this->render('user/show.html.twig', [
            'user' => $this->userRepository->find($id)
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="edit_user", methods={"GET", "PUT"})
     */
    public function editAction(Request $request, $id) {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('edit_user', [
                'id' => $id
            ]),
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add('success', 'User details is updated successfully !!!');

            return $this->redirectToRoute('show_user', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteAction($id) {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->add('success', 'User details is deleted successfully !!!');

        return new JsonResponse([
            'success' => true,
            'message' => 'User is removed successfully!!!'
        ]);
    }
}
