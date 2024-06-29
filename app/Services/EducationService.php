<?php

namespace App\Services;

use App\Http\Resources\ExplanationResource;
use App\Http\Resources\OrderExplanationResource;
use App\Models\Explanation;
use App\Models\OrderExplanation;
use App\Models\Title;
use App\Repositories\EducationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * A service class responsible for handling education-related logic.
 */

class EducationService
    {

        /**
         * Constructor to initialize EducationService with dependencies.
         */
        protected $educationRepository;
        private $validationService;



        public function __construct(EducationRepository $educationRepository, ValidationService $validationService)
        {
            $this->educationRepository = $educationRepository;
            $this->validationService = $validationService;
        }

        /**
         * Retrieve all classrooms.
         */
        public function getAllClassroom()
        {
            return $this->educationRepository->getAllClassroom();
        }

        /**
         * Retrieve subjects for a specific classroom.
         */
        public function getSubjectsForClassroom($classroomId)
        {
            return $this->educationRepository->getSubjectsForClassroom($classroomId);
        }


        /**
         * Retrieve titles for a specific subject class.
         */
        public function getTitlesForSubjectClass($subjectClassId)
        {
            return $this->educationRepository->getTitlesForSubjectClass($subjectClassId);
        }

        /**
         * Order explanations.
         */
        public function orderExplanations(array $data)
        {
            $this->validationService->validateorderExplanations($data);
            $orderExplanation = $this->educationRepository->orderExplanations($data);
            return new OrderExplanationResource($orderExplanation);

        }

        /**
         * Approve an order explanation.
         */
        public function approveorderExplanation($orderId)
        {
            try {
            $orderExplanation=$this->educationRepository->findOrderExplanationById($orderId);
            if (!$orderExplanation) {
                throw new \Exception('لم يتم العثور على الطلب.');
            }

            if ($orderExplanation->approvals >=3) {
                throw new \Exception('الحد الأقصى للموافقات على الطلب قد تم تجاوزه.');
            }
            $explanation =$this->educationRepository->createExplanation($orderId,$orderExplanation);
            $orderExplanation=$this->educationRepository->updateApprovalsCount($orderExplanation);

            return new OrderExplanationResource($explanation);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }

        /**
         * Retrieve all order explanations.
         */
        public function getAllOrderExplanations()
        {
            $OrderExplanations= $this->educationRepository->getAllOrderExplanations();
            return OrderExplanationResource::collection($OrderExplanations);
        }

        /**
         * Retrieve order explanations for a child.
         */
        public function getChildOrderExplanations()
        {
            $loggedInChildId = Auth::id();
            $OrderExplanations= $this->educationRepository->getChildOrderExplanations($loggedInChildId);
            return OrderExplanationResource::collection($OrderExplanations);
        }

        /**
         * Retrieve details of an order explanation.
         */
        public function OrderExplanationDetails($id)
        {
            $orderExplanation= $this->educationRepository->OrderExplanationDetails($id);
            return new OrderExplanationResource($orderExplanation);


        }

    /**
     * Retrieve details of an  explanation.
     */
    public function ExplanationDetails($id)
    {
        $Explanation= $this->educationRepository->ExplanationDetails($id);
        return new ExplanationResource($Explanation);
    }

        /**
         * Retrieve pending explanations for the current user.d
         */
    public function getUserPendingExplanations()
    {
        $userId = Auth::id();
        $pendingExplanations = $this->educationRepository->getUserPendingExplanations($userId);

        return ExplanationResource::collection($pendingExplanations);
    }

        /**
         * Retrieve all pending explanations.
         */
        public function getAllPendingExplanations()
        {
            $pendingExplanations = $this->educationRepository->getAllPendingExplanations();
            return ExplanationResource::collection($pendingExplanations);
        }

        /**
         * Retrieve explanations uploaded by the current user.
         */
        public function getUserUploadedExplanations()
        {
            $userId = Auth::id();
            $uploadedExplanations = $this->educationRepository->getUserUploadedExplanations($userId);
            return ExplanationResource::collection($uploadedExplanations);
        }

        /**
         * Retrieve all uploaded explanations.
         */
        public function getAllUploadedExplanations()
        {

            $uploadedExplanations = $this->educationRepository->getAllUploadedExplanations();
            return ExplanationResource::collection($uploadedExplanations);
        }

        /**
         * Retrieve explanations rejected by the current user.
         */
        public function getUserRejectedExplanations()
        {
            $userId = Auth::id();
            $rejectedExplanations= $this->educationRepository->getUserRejectedExplanations($userId);
            return ExplanationResource::collection($rejectedExplanations);
        }

        /**
         * Retrieve all rejected explanations.
         */
        public function getAllRejectedExplanations()
        {

            $rejectedExplanations= $this->educationRepository->getAllRejectedExplanations();
            return ExplanationResource::collection($rejectedExplanations);
        }

        /**
         * Retrieve explanations approved by the current user.
         */
        public function getUserApprovedExplanations()
        {
            $userId = Auth::id();
            $approvedExplanations =$this->educationRepository->getUserApprovedExplanations($userId);
            return ExplanationResource::collection($approvedExplanations);
        }

        /**
         * Retrieve all approved explanations.
         */
        public function getAllApprovedExplanations()
        {

            $approvedExplanations =$this->educationRepository->getAllApprovedExplanations();
            return ExplanationResource::collection($approvedExplanations);
        }

        /**
         * Retrieve explanations by title.
         */
        public function getExplanationsByTitle($titleId)
        {
            $explanations= $this->educationRepository->getExplanationsByTitle($titleId);
            return ExplanationResource::collection($explanations);
        }

        /**
         * Approve an explanation.
         */
        public function approveExplanation($explanation)
        {
            $approvedExplanation = $this->educationRepository->approveExplanation($explanation);

            return new ExplanationResource($approvedExplanation);
        }
        /**
         * Reject an explanation.
         */
        public function rejectedExplanation($explanation,array $data)
        {
            $rejectedExplanation= $this->educationRepository->rejectedExplanation($explanation,$data);
            return new ExplanationResource($rejectedExplanation);
        }


}
