<?php

namespace App\Http\Controllers;

use App\Models\Explanation;
use App\Models\OrderExplanation;
use App\Models\Title;

use App\Services\CloudinaryService;
use App\Services\EducationService;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Tag\ImageTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Cloudinary\Cloudinary;
use Cloudinary\Api\Admin\AdminApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
//use Cloudinary\Api\Utils\ApiUtils;

use Cloudinary\Configuration\Configuration;


use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Cloudinary\Asset\AuthToken;


use Cloudinary\Api\Upload\UploadApi;


use Cloudinary\Configuration\UrlConfig;
use Illuminate\Support\Facades\Http;
use Spatie\FlareClient\Http\Client;


/*
    |----------------------------------------------------------------------------------
    | This Controller Contains all the Education Management Functions
    |-----------------------------------------------------------------------------------
    */

class EducationController extends Controller
{
    protected $educationService;
    protected $cloudinaryService;

    public function __construct(EducationService $educationService, CloudinaryService $cloudinaryService)
    {
        $this->educationService = $educationService;
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Retrieve all classrooms.
     */
    public function getAllClassroom()
    {
        try {
            $classroom = $this->educationService->getAllClassroom();
            return response()->json(['data' => $classroom]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve subjects for a specific classroom.
     */
    public function getSubjectsForClassroom($classroomId)
    {
        try {

            $subjects = $this->educationService->getSubjectsForClassroom($classroomId);
            return response()->json(['data' => $subjects]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve titles for a specific subject class.
     */

    public function getTitlesForSubjectClass($subjectClassId)
    {
        $titles = $this->educationService->getTitlesForSubjectClass($subjectClassId);

        return response()->json(['data' => $titles]);
    }

    /**
     * Order explanations.
     */
    public function orderExplanations(Request $request)
    {
        return $this->educationService->orderExplanations($request->all());
    }

    /**
     * Approve an order explanation.
     */
    public function approveorderExplanation($orderId, Request $request)
    {

        try {

            $explanation = $this->educationService->approveorderExplanation($orderId);
            return response()->json($explanation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    /**
     * Retrieve all order explanations.
     */
    public function getAllOrderExplanations()
    {
        try {
            $explanations = $this->educationService->getAllOrderExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve child order explanations.
     */
    public function getChildOrderExplanations()
    {

        try {
            $explanations = $this->educationService->getChildOrderExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve order explanation details.
     */
    public function OrderExplanationDetails($id)
    {
        try {
            $orderExplanation = $this->educationService->OrderExplanationDetails($id);
            return response()->json($orderExplanation);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve user's pending explanations.
     */
    public function getUserPendingExplanations()
    {
        try {
            $explanations = $this->educationService->getUserPendingExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function getAllPendingExplanations()
    {
        try {
            $explanations = $this->educationService->getAllPendingExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    /**
     * Retrieve explanations uploaded by the current user.
     */
    public function getUserUploadedExplanations()
    {
        try {
            $explanations = $this->educationService->getUserUploadedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve all uploaded explanations.
     */
    public function getAllUploadedExplanations()
    {
        try {
            $explanations = $this->educationService->getAllUploadedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve explanations rejected by the current user.
     */
    public function getUserRejectedExplanations()
    {
        try {
            $explanations = $this->educationService->getUserRejectedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve all rejected explanations.
     */
    public function getAllRejectedExplanations()
    {
        try {
            $explanations = $this->educationService->getAllRejectedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve explanations approved by the current user.
     */
    public function getUserApprovedExplanations()
    {
        try {
            $explanations = $this->educationService->getUserApprovedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve all approved explanations.
     */
    public function getAllApprovedExplanations()
    {
        try {
            $explanations = $this->educationService->getUserApprovedExplanations();
            return response()->json($explanations);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Retrieve explanations by their associated title.
     */
    public function getExplanationsByTitle($titleId)
    {

        try {
            $explanations = $this->educationService->getExplanationsByTitle($titleId);
            return response()->json(['data' => $explanations]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve  explanation details.
     */
    public function ExplanationDetails($id)
    {
        try {
            $orderExplanation = $this->educationService->ExplanationDetails($id);
            return response()->json($orderExplanation);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Approve an explanation.
     */
    public function approveExplanation($explanation)
    {
        try {
            $explanation = $this->educationService->approveExplanation($explanation);

            return response()->json($explanation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Reject an explanation.
     */
    public function rejectedExplanation($explanation, Request $request)
    {
        try {
            $explanation = $this->educationService->rejectedExplanation($explanation, $request->all());

            return response()->json($explanation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function saveExplanationUrl(Request $request, $explanatId)
    {
        try {
            $url = $request->input('url');
            $title = $request->input('title');
            $explanatId = $this->cloudinaryService->saveExplanationUrl($explanatId, $url, $title);
            return response()->json(['message' => 'Explanation URL saved successfully', 'explanatId' => $explanatId]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
