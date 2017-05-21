<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\BlogPostsType;
use AppBundle\Entity\BlogPosts;

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
    /**
    * @param Request $request
    * @Route("/create", name="create")
    */
    public function createAction(Request $request)
    {
        $form = $this->createForm(BlogPostsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPosts = $form->getData();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($blogPosts);
            $em->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('AppBundle:BlogPosts:create.html.twig', ['form' => $form->createView()]);
    }

     /**
     * @param Request  $request
     * @param BlogPost $blogPost
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{blogPost}", name="edit")
     */
    public function editAction(Request $request, BlogPosts $blogPost)
    {
        $form = $this->createForm(BlogPostsType::class, $blogPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();
            return $this->redirectToRoute('list');
        }

        return $this->render('AppBundle:BlogPosts:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
     /**
     * @param Request  $request
     * @param BlogPost $blogPost
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{blogPost}", name="delete")
     */
    public function deleteAction(Request $request, BlogPosts $blogPost)
    {
        if ($blogPost ==null) {
            return $this->redirectToRoute('list');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($blogPost);
        $em->flush();
        return $this->redirectToRoute('list');
    }

}
