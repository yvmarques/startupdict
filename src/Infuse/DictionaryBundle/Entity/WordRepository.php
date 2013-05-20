<?php

namespace Infuse\DictionaryBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WordRepository extends EntityRepository
{
    public function findAllStartWith($letter) {
        return $this->getEntityManager()
            ->createQuery("SELECT w FROM InfuseDictionaryBundle:Word w WHERE w.word LIKE ':letter' ORDER BY w.word ASC")
            ->setParameter('letter', $letter . '%')
            ->getResult();
    }
}
