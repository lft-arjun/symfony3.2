<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\BlogPostsType;
use AppBundle\Entity\BlogPosts;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Action\RowAction;

class BlogPostsController extends Controller
{
    /**
     * @Route("/", name="list")
     */
    public function listAction()
    {
    	// $em = $this->getDoctrine()->getEntityManager();
    	// // $blogPosts = $em->getRepository('AppBundle:BlogPosts')->findAll();
    	// $blogPosts = $em->getRepository('AppBundle:BlogPosts')->findAllOrderedByTitle();
     //    return $this->render('AppBundle:BlogPosts:list.html.twig', ['blogPosts' => $blogPosts]);
        $source = new Entity('AppBundle:BlogPosts');

        // Get a Grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setId('blog_post_grid');
        $grid->addExport(new CSVExport('CSV Export'));
        $rowAction2 = new RowAction('Edit', 'blog_post_edit',true, '_self', array('class' => 'grid_edit_action'));
        $rowAction2->setRouteParameters(array('id'));
        $grid->addRowAction($rowAction2);
        $rowActionDel = new RowAction('Delete', 'blog_post_delete',true, '_self', array('class' => 'grid_delete_action'));
        $rowActionDel->setRouteParameters(array('id'));
        $grid->addRowAction($rowActionDel);

        // Return the response of the grid to the template
        return $grid->getGridResponse('AppBundle:BlogPosts:listgrid.html.twig');
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
     * @param BlogPosts $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="blog_post_edit")
     */
    public function editAction(Request $request, BlogPosts $id)
    {
        
        $form = $this->createForm(BlogPostsType::class, $id);

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
     * @param BlogPosts $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{id}", name="blog_post_delete")
     */
    public function deleteAction(Request $request, BlogPosts $id)
    {
        if ($id ==null) {
            return $this->redirectToRoute('list');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('list');
    }

}
