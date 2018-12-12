<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use KejawenLab\Application\SemartHris\Component\Reason\ReasonType;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReasonRepository extends Repository implements ReasonRepositoryInterface
{
    /**
     * @param string $type
     *
     * @return ReasonInterface[]
     */
    public function findByType(string $type): array
    {
        if (!$type) {
            return [];
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('r.id, r.code, r.name');
        $queryBuilder->from($this->entityClass, 'r');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('r.type', $queryBuilder->expr()->literal($type)));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $id
     *
     * @return ReasonInterface|null
     */
    public function find(?string $id): ? ReasonInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param string $code
     *
     * @return ReasonInterface|null
     */
    public function findAbsentReasonByCode(string $code): ? ReasonInterface
    {
        if (!$code) {
            return null;
        }

        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['code' => StringUtil::uppercase($code), 'type' => ReasonType::ABSENT_CODE]);
    }
}
