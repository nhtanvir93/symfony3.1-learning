<?php

namespace AppBundle\Controller;

use AppBundle\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/test")
 */
class TestController extends Controller
{
    private $greeting;
    protected $container;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @Route("/get-project-name", name="get_project_name")
     */
    public function getProjectNameAction(Request $request) {

//        throw $this->createNotFoundException('Testing Error');

        return $this->render('test/default.html.twig', [
            'projectName' => 'Symfony Final',
            'greetingMessage' => $this->greeting->getDefault('Tanvir')
        ]);
    }
}
