<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Screening\PhoneMasker;
use KejawenLab\Application\SemartHris\Component\Screening\RecruiterDecision;
use KejawenLab\Application\SemartHris\Component\Screening\Service\CallScreenEngine;
use KejawenLab\Application\SemartHris\Repository\ScreeningCandidateRepository;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * JSON endpoints for the AI phone screening workflow.
 * List and form screens stay in EasyAdmin (config/admin/screening.yaml),
 * this controller only handles launch confirmation, batch start,
 * and the human recruiter decision.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningController extends AdminController
{
    /**
     * Pre-flight payload for the call confirmation modal.
     * Phones stay masked in list views, full numbers are only
     * exposed here so the recruiter can verify before dialing.
     *
     * @Route("/screening/confirm", name="screening_confirm", options={"expose"=true})
     *
     * @param Request $request ?ids[]=uuid
     *
     * @return Response
     */
    public function confirmAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_HRSTAFF');
        $ids = array_values(array_filter((array) $request->query->get('ids', [])));
        /** @var ScreeningCandidateRepository $repository */
        $repository = $this->container->get(ScreeningCandidateRepository::class);
        $candidates = $repository->findByIds($ids);

        $rows = [];
        foreach ($candidates as $candidate) {
            $rows[] = [
                'id' => $candidate->getId(),
                'name' => $candidate->getName(),
                'phone' => $candidate->getPhone(),
                'maskedPhone' => PhoneMasker::mask($candidate->getPhone()),
                'position' => $candidate->getPosition(),
            ];
        }

        /** @var CallScreenEngine $engine */
        $engine = $this->container->get(CallScreenEngine::class);

        return new JsonResponse([
            'candidates' => $rows,
            'count' => count($rows),
            'mode' => $engine->getMode(),
            'isLive' => $engine->isLive(),
        ]);
    }

    /**
     * @Route("/screening/start", name="screening_start", options={"expose"=true}, methods={"POST"})
     *
     * @param Request $request {candidateIds: [uuid]}
     *
     * @return Response
     */
    public function startAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_HRSTAFF');
        $payload = json_decode((string) $request->getContent(), true);
        if (!is_array($payload)) {
            $payload = [];
        }
        $ids = isset($payload['candidateIds']) && is_array($payload['candidateIds']) ? $payload['candidateIds'] : [];

        /** @var ScreeningCandidateRepository $repository */
        $repository = $this->container->get(ScreeningCandidateRepository::class);
        /** @var CallScreenEngine $engine */
        $engine = $this->container->get(CallScreenEngine::class);

        $manager = $this->getDoctrine()->getManager();
        $started = [];
        foreach ($repository->findByIds($ids) as $candidate) {
            $candidate->setStatus($engine->launchStatus());
            $manager->persist($candidate);
            $started[] = $candidate->getId();
        }
        $manager->flush();

        return new JsonResponse([
            'started' => $started,
            'count' => count($started),
            'mode' => $engine->getMode(),
        ]);
    }

    /**
     * Records the human recruiter decision on a screening.
     * AI recommends, the recruiter decides.
     *
     * @Route("/screening/{id}/decision", name="screening_decision", options={"expose"=true}, methods={"POST"})
     *
     * @param string  $id screening uuid
     * @param Request $request {decision: interview|backup|pass}
     *
     * @return Response
     */
    public function decideAction(string $id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_HRSTAFF');
        if (!UuidUtil::isValid($id)) {
            return new JsonResponse(['error' => 'Invalid screening id.'], Response::HTTP_BAD_REQUEST);
        }

        $payload = json_decode((string) $request->getContent(), true);
        $decision = is_array($payload) && isset($payload['decision']) ? (string) $payload['decision'] : '';
        if (!in_array($decision, RecruiterDecision::getDecisions(), true)) {
            return new JsonResponse(['error' => 'Invalid decision.'], Response::HTTP_BAD_REQUEST);
        }

        /** @var \KejawenLab\Application\SemartHris\Entity\Screening|null $screening */
        $screening = $this->getDoctrine()->getManager()->getRepository('KejawenLab\Application\SemartHris\Entity\Screening')->find($id);
        if (!$screening) {
            return new JsonResponse(['error' => 'Screening not found.'], Response::HTTP_NOT_FOUND);
        }

        $screening->setRecruiterDecision($decision);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($screening);
        $manager->flush();

        return new JsonResponse(['id' => $id, 'decision' => $decision]);
    }

    /**
     * @Route("/screening-candidate/search", name="screening_candidate_search", options={"expose"=true})
     *
     * @param Request $request ?q=name or phone fragment
     *
     * @return Response
     */
    public function searchCandidatesAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_HRSTAFF');
        /** @var ScreeningCandidateRepository $repository */
        $repository = $this->container->get(ScreeningCandidateRepository::class);
        $candidates = $repository->search((string) $request->query->get('q', ''));

        foreach ($candidates as $index => $candidate) {
            if (isset($candidate['phone'])) {
                $candidates[$index]['maskedPhone'] = PhoneMasker::mask($candidate['phone']);
                unset($candidates[$index]['phone']);
            }
        }

        return new JsonResponse(['candidates' => $candidates]);
    }
}
