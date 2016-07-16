<?php

namespace SICBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SituacionSaludRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SituacionSaludRepository extends EntityRepository
{
	public function padecencia($situacion)
    {
         return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('m')
                ->from('SICBundle:SituacionSalud', 'm')
                ->innerJoin('m.padecencia', 'e')
                ->where('e.id = :exampleid' )
                ->setParameter('exampleid', $situacion->getId() )
                ->getQuery()
                ->getResult();
    }

    public function ayudaEspecial($situacion)
    {
         return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('m')
                ->from('SICBundle:SituacionSalud', 'm')
                ->innerJoin('m.ayudaEspecial', 'e')
                ->where('e.id = :exampleid' )
                ->setParameter('exampleid', $situacion->getId() )
                ->getQuery()
                ->getResult();
    }

    public function situacionExclusion($situacion)
    {
         return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('m')
                ->from('SICBundle:SituacionSalud', 'm')
                ->innerJoin('m.situacionExclusion', 'e')
                ->where('e.id = :exampleid' )
                ->setParameter('exampleid', $situacion->getId() )
                ->getQuery()
                ->getResult();
    }
}
