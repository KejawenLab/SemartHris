<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use KejawenLab\Semart\Collection\Collection;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class QueryService
{
    private $connections;

    private $registryManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->connections = $managerRegistry->getConnections();
        $this->registryManager = $managerRegistry;
    }

    public function getConnections()
    {
        return array_keys($this->connections);
    }

    public function runQuery(string $query, string $connection = 'default'): array
    {
        $output = [
            'status' => true,
            'columns' => [],
            'records' => [],
            'total' => 0,
        ];

        $sqlRunner = $this->registryManager->getConnection(Str::make($connection)->lowercase()->__toString());
        try {
            /** @var \PDOStatement $statement */
            $statement = $sqlRunner->executeQuery($query);
            $results = $statement->fetchAll();

            $columns = [];
            if (!empty($results)) {
                $columns = array_keys($results[0]);
            }

            $output['columns'] = $columns;
            $output['records'] = $results;
            $output['total'] = $results ? \count($results) : 0;
        } catch (\Exception $e) {
            $messages = explode(':', $e->getMessage());

            $reason = explode(';', $messages[3]);
            if (1 < \count($reason)) {
                $description = $reason[1];
            } else {
                $description = $reason[0];
            }

            $output['status'] = false;
            $output['columns'] = ['error', 'reason', 'description'];
            $output['records'] = [[Str::make($messages[1])->trim()->__toString(), Str::make($messages[2])->trim()->__toString(), Str::make($description)->trim()->uppercaseFirst()->__toString()]];
        }

        return $output;
    }

    public function getTables(string $connection = 'default')
    {
        /** @var AbstractSchemaManager $schemaManager */
        $schemaManager = $this->registryManager->getConnection(Str::make($connection)->lowercase()->__toString())->getSchemaManager();

        return Collection::collect($schemaManager->listTables())
            ->merge($schemaManager->listViews())
            ->map(static function ($value) {
                /* @var AbstractAsset $value */
                return $value->getName();
            })
            ->toArray()
        ;
    }
}
