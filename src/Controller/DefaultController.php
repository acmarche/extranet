<?php


namespace AcMarche\Extranet\Controller;

use AcMarche\Extranet\Security\LocatorRoles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 */
class DefaultController extends AbstractController
{
    private LocatorRoles $locatorRoles;

    public function __construct(LocatorRoles $locatorRoles)
    {
        $this->locatorRoles = $locatorRoles;
    }

    /**
     * @Route("/", name="extranet_home")
     */
    public function index(): Response
    {
        $roles = [[]];
        foreach ($this->locatorRoles->get() as $t) {
            $roles[] = $t->roles();
        }
        $p = array_merge(...$roles);
        dump($p);

        return $this->render(
            '@Extranet/default/index.html.twig',
            [

            ]
        );
    }
}
