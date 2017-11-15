<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Service\ValidateStateType;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryComponentRepository extends Repository implements ComponentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return null|ComponentInterface
     */
    public function find(string $id): ? ComponentInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param string $code
     *
     * @return ComponentInterface|null
     */
    public function findByCode(string $code): ? ComponentInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['code' => $code]);
    }

    /**
     * @param string $state
     *
     * @return ComponentInterface[]
     */
    public function findByState(string $state): array
    {
        if (!ValidateStateType::isValidType($state)) {
            return [];
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'c');
        $queryBuilder->select('c.id, c.code, c.name');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.state', $queryBuilder->expr()->literal($state)));
        $queryBuilder->andWhere($queryBuilder->expr()->neq('c.code', $queryBuilder->expr()->literal(SettingUtil::get(SettingUtil::OVERTIME_COMPONENT_CODE))));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.fixed', $queryBuilder->expr()->literal(false)));

        return $queryBuilder->getQuery()->getResult();
    }
}
