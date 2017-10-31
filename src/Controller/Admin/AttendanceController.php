<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceImporter;
use KejawenLab\Application\SemartHris\Component\Setting\Setting;
use KejawenLab\Application\SemartHris\Form\Manipulator\AttendanceManipulator;
use KejawenLab\Application\SemartHris\Repository\AttendanceRepository;
use League\Csv\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceController extends AdminController
{
    const ATTENDANCE_UPLOAD_SESSION = 'ATTENDANCE_UPLOAD_FILE';

    /**
     * @Route("/attendance/upload", name="upload_attendance")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAttendanceAction(Request $request): Response
    {
        $this->initialize($request);
        $this->request = $request;

        if (null === $request->request->get('entity')) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ));
        }

        /** @var UploadedFile $attendance */
        $attendance = $request->files->get('attendance');
        if (null === $attendance) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ));
        }

        $destination = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), Setting::get(Setting::getKey('SEMART_UPLOAD_DESTINATION')), Setting::get(Setting::getKey('SEMART_ATTENDANCE_UPLOAD_PATH')));
        $fileName = sprintf('%s.%s', (new \DateTime())->format('Y_m_d_H_i_s'), $attendance->guessExtension());
        $attendance->move($destination, $fileName);

        $request->getSession()->set(self::ATTENDANCE_UPLOAD_SESSION, sprintf('%s/%s', $destination, $fileName));

        /** @var Reader $processor */
        $processor = Reader::createFromPath(sprintf('%s/%s', $destination, $fileName));
        $processor->setHeaderOffset(0);

        return $this->render('app/attendance/upload_list.html.twig', ['records' => $processor->getRecords()]);
    }

    /**
     * @Route("/attendance/process_upload", name="process_upload_attendance")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processUploadAction(Request $request)
    {
        /** @var Reader $processor */
        $processor = Reader::createFromPath($request->getSession()->get(self::ATTENDANCE_UPLOAD_SESSION));
        $processor->setHeaderOffset(0);

        $importer = $this->container->get(AttendanceImporter::class);
        $importer->import($processor->getRecords());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'attendanceDate',
            'sortDirection' => 'DESC',
            'entity' => 'Attendance',
        ));
    }

    /**
     * @Route("/attendance/process", name="process_attendance", options={"expose"=true})
     * @Method("POST")
     *
     * @return Response
     */
    public function processAction()
    {
        return new JsonResponse(['message' => 'OK']);
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
        $startDate = \DateTime::createFromFormat('d-m-Y', $this->request->query->get('startDate', date('01-m-Y')));
        $endDate = \DateTime::createFromFormat('d-m-Y', $this->request->query->get('endDate', date('t-m-Y')));
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(AttendanceRepository::class)->getFilteredAttendance($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }

    /**
     * @param object $entity
     * @param string $view
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        return $this->container->get(AttendanceManipulator::class)->manipulate($builder, $entity);
    }
}
