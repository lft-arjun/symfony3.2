<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogPostsController extends Controller
{
    /**
     * @Route("/", name="list")
     */
    public function listAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	// $blogPosts = $em->getRepository('AppBundle:BlogPosts')->findAll();
    	$blogPosts = $em->getRepository('AppBundle:BlogPosts')->findAllOrderedByTitle();
        return $this->render('AppBundle:BlogPosts:list.html.twig', ['blogPosts' => $blogPosts]);
    }

}
