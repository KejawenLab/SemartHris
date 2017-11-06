<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeImporter;
use KejawenLab\Application\SemartHris\Repository\OvertimeRepository;
use KejawenLab\Application\SemartHris\Util\SettingUtil;
use League\Csv\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeController extends AdminController
{
    const OVERTIME_UPLOAD_SESSION = 'OVERTIME_UPLOAD_FILE';

    /**
     * @Route("/overtime/upload", name="upload_overtime")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadOvertimeAction(Request $request): Response
    {
        $this->initialize($request);
        $this->request = $request;

        if (null === $request->request->get('entity')) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'overtimeDate',
                'sortDirection' => 'DESC',
                'entity' => 'Overtime',
            ));
        }

        /** @var UploadedFile $overtime */
        $overtime = $request->files->get('overtime');
        if (null === $overtime) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'overtimeDate',
                'sortDirection' => 'DESC',
                'entity' => 'Overtime',
            ));
        }

        $destination = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), SettingUtil::get(SettingUtil::UPDATE_DESTIONATION), SettingUtil::get(SettingUtil::OVERTIME_UPLOAD_PATH));
        $fileName = sprintf('%s.%s', (new \DateTime())->format('Y_m_d_H_i_s'), $overtime->guessExtension());
        $overtime->move($destination, $fileName);

        $request->getSession()->set(self::OVERTIME_UPLOAD_SESSION, sprintf('%s/%s', $destination, $fileName));

        /** @var Reader $processor */
        $processor = Reader::createFromPath(sprintf('%s/%s', $destination, $fileName));
        $processor->setHeaderOffset(0);

        return $this->render('app/overtime/upload_list.html.twig', ['records' => $processor->getRecords()]);
    }

    /**
     * @Route("/overtime/process_upload", name="process_upload_overtime")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processUploadAction(Request $request)
    {
        /** @var Reader $processor */
        $processor = Reader::createFromPath($request->getSession()->get(self::OVERTIME_UPLOAD_SESSION));
        $processor->setHeaderOffset(0);

        $importer = $this->container->get(OvertimeImporter::class);
        $importer->import($processor->getRecords());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'overtimeDate',
            'sortDirection' => 'DESC',
            'entity' => 'Attendance',
        ));
    }

    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return array
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $startDate = $this->request->query->get('startDate', null);
        if (!$startDate) {
            $startDate = date(SettingUtil::get(SettingUtil::FIRST_DATE_FORMAT));
        }

        $endDate = $this->request->query->get('endDate', null);
        if (!$endDate) {
            $endDate = date(SettingUtil::get(SettingUtil::FIRST_DATE_FORMAT));
        }

        $startDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $startDate);
        $endDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $endDate);
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(OvertimeRepository::class)->getFilteredOvertime($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
