<?php

namespace AppBundle\Repository;

/**
 * BlogPostsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogPostsRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllOrderedByTitle()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT b FROM AppBundle:BlogPosts b ORDER BY b.title ASC'
            )
            ->getResult();
    }
}