<?php

namespace Infuse\DictionaryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'Yvan');
    }
}
