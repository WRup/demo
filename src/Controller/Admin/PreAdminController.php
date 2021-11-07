<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controller used to enter admin page privileges.
 *
 *
 * @Route("/lab/admin")
 * @IsGranted("ROLE_ADMIN")
 *
 */
class PreAdminController extends AbstractController
{

    /**
     * @Route("/home", methods="GET", name="lab_admin_home")
     * @Cache(smaxage="10")
     *
     */
    public function index(): Response
    {
        return $this->render('admin/homepage.html.twig');
    }

}