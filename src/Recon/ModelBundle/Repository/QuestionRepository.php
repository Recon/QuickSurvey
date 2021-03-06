<?php

namespace Recon\ModelBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends EntityRepository
{

    public function getMaxPosition()
    {
        $query = $this->getEntityManager()->createQueryBuilder()
        ->from('Recon\ModelBundle\Entity\Question', 'q')
        ->select('MAX(q.position) AS max_position');

        return $query->getQuery()->getSingleScalarResult();
    }

}
