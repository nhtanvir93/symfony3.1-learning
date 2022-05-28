<?php

namespace AppBundle\Controller;

use AppBundle\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Role;

class RoleController extends Controller
{
    private $entityManager;
    private $role;
    private $roleRepository;
    protected $container;

    public function __construct(ContainerInterface $container, Role $role)
    {
        $this->setContainer($container);

        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $this->role = $role;
        $this->roleRepository = $this->entityManager->getRepository('AppBundle:Role');
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @Route("/roles", name="role_list")
     */
    public function indexAction(Request $request) {
        return $this->render('role/index.html.twig', [
            'roles' => $this->roleRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/roles/create", name="create_role", methods={"GET","POST"})
     */
    public function createAction(Request $request) {
        $this->role->setCreatedAt(
            new \DateTime(
                'now',
                new \DateTimeZone('Asia/Dhaka')
            )
        );

        $form = $this->createForm(RoleType::class, $this->role, [
            'action' => $this->generateUrl('create_role'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($this->role);
            $this->entityManager->flush();

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add('success', 'New Role is created successfully !!!');

            return $this->redirectToRoute('show_role', [
                'id' => $this->role->getId()
            ]);
        }

        return $this->render('role/create.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('create_role')
        ]);
    }

    /**
     * @Route("/roles/{id}", name="show_role")
     */
    public function showAction($id) {
        return $this->render('role/show.html.twig', [
            'role' => $this->roleRepository->find($id)
        ]);
    }

    /**
     * @Route("/roles/{id}/edit", name="edit_role", methods={"GET", "PUT"})
     */
    public function editAction(Request $request, $id) {
        $role = $this->roleRepository->find($id);

        $form = $this->createForm(RoleType::class, $role, [
            'action' => $this->generateUrl('edit_role', [
                'id' => $id
            ]),
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add('success', 'Role details is updated successfully !!!');

            return $this->redirectToRoute('show_role', [
                'id' => $role->getId()
            ]);
        }

        return $this->render('role/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-role/{id}", name="delete_role", methods={"DELETE"})
     */
    public function deleteAction($id) {
        $role = $this->roleRepository->find($id);

        $this->entityManager->remove($role);
        $this->entityManager->flush();

        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->add('success', 'Role details is deleted successfully !!!');

        return new JsonResponse([
            'success' => true,
            'message' => 'Role is removed successfully!!!'
        ]);
    }
}
