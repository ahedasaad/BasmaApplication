<?php

namespace App\Services;

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
            return $this->educationRepository->orderExplanations($data);
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

            return $explanation;
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }

        /**
         * Retrieve all order explanations.
         */
        public function getAllOrderExplanations()
        {
            return $this->educationRepository->getAllOrderExplanations();
        }

        /**
         * Retrieve order explanations for a child.
         */
        public function getChildOrderExplanations()
        {
            $loggedInChildId = Auth::id();
            return $this->educationRepository->getChildOrderExplanations($loggedInChildId);
        }

        /**
         * Retrieve details of an order explanation.
         */
        public function OrderExplanationDetails($id)
        {
            return $this->educationRepository->OrderExplanationDetails($id);
        }

    /**
     * Retrieve details of an  explanation.
     */
    public function ExplanationDetails($id)
    {
        return $this->educationRepository->ExplanationDetails($id);
    }

        /**
         * Retrieve pending explanations for the current user.d
         */
        public function getUserPendingExplanations()
        {
            $userId = Auth::id();
            return $this->educationRepository->getUserPendingExplanations($userId);
        }

        /**
         * Retrieve all pending explanations.
         */
        public function getAllPendingExplanations()
        {

            return $this->educationRepository->getAllPendingExplanations();
        }

        /**
         * Retrieve explanations uploaded by the current user.
         */
        public function getUserUploadedExplanations()
        {
            $userId = Auth::id();
            return $this->educationRepository->getUserUploadedExplanations($userId);
        }

        /**
         * Retrieve all uploaded explanations.
         */
        public function getAllUploadedExplanations()
        {

            return $this->educationRepository->getAllUploadedExplanations();
        }

        /**
         * Retrieve explanations rejected by the current user.
         */
        public function getUserRejectedExplanations()
        {
            $userId = Auth::id();
            return $this->educationRepository->getUserRejectedExplanations($userId);
        }

        /**
         * Retrieve all rejected explanations.
         */
        public function getAllRejectedExplanations()
        {

            return $this->educationRepository->getAllRejectedExplanations();
        }

        /**
         * Retrieve explanations approved by the current user.
         */
        public function getUserApprovedExplanations()
        {
            $userId = Auth::id();
            return $this->educationRepository->getUserApprovedExplanations($userId);
        }

        /**
         * Retrieve all approved explanations.
         */
        public function getAllApprovedExplanations()
        {

            return $this->educationRepository->getAllApprovedExplanations();
        }

        /**
         * Retrieve explanations by title.
         */
        public function getExplanationsByTitle($titleId)
        {
            return $this->educationRepository->getExplanationsByTitle($titleId);
        }

        /**
         * Approve an explanation.
         */
        public function approveExplanation($explanation)
        {
            return $this->educationRepository->approveExplanation($explanation);
        }

        /**
         * Reject an explanation.
         */
        public function rejectedExplanation($explanation,array $data)
        {
            return $this->educationRepository->rejectedExplanation($explanation,$data);
        }


}
