<?php

namespace App\Http\Controllers;

use App\Models\Explanation;
use App\Models\OrderExplanation;
use App\Models\Title;

use App\Services\EducationService;
use Cloudinary\Tag\ImageTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Cloudinary\Cloudinary;
use Cloudinary\Api\Admin\AdminApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Cloudinary\Asset\AuthToken;


use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

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

    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
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
            return response()->json(['data'=>$subjects]);
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

        return response()->json(['data'=>$titles]);
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
            return response()->json($explanations);

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
    public function rejectedExplanation($explanation)
    {
        try {
            $explanation = $this->educationService->rejectedExplanation($explanation);

            return response()->json($explanation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

//
//CLOUDINARY_CLOUD_NAME=dftvov92g
//CLOUDINARY_API_KEY=952944418364724
//CLOUDINARY_API_SECRET=6H2jlDuaNl8ZP-g0oyJWRcB71BU
//CLOUDINARY_URL=cloudinary://952944418364724:6H2jlDuaNl8ZP-g0oyJWRcB71BU@dftvov92g





    public function uploadSignature(Request $request)
    {
        // Generate timestamp
        $timestamp = time();

        // Upload parameters
        $params = [
            'timestamp' => $timestamp,
            'upload_preset' => 'ml_default',
        ];

        $signature = $this->generateSignature($params);
        $params['signature'] = $signature;
        $file = $request->file('image');
        $result = Cloudinary::uploadApi()->upload($file->getRealPath(), $params); // Pass the real path of the file

        return response()->json($result);
    }

    private function generateSignature($params)
    {
        $stringToSign = http_build_query($params) .config('cloudinary.api_secret');

        $signature = hash('sha256', $stringToSign);

        return $signature;
    }




    public function uploadImage(Request $request)
    {
        // استلام التوقيع الرقمي والمعلومات الأخرى من المشروع الآخر
        $signature = $this->getSignature();
        $cloudinary_cloud_name = 'dftvov92g';
        $cloudinary_api_key = '952944418364724';

        //CLOUDINARY_CLOUD_NAME=dftvov92g
        //CLOUDINARY_API_KEY=952944418364724
        $image = $request->file('image');

        // إعداد البيانات لرفع الصورة
        $data = [
            'file' => $image,
            'folder' => 'arwa', // المجلد المحدد في حساب Cloudinary
            'api_key' => $cloudinary_api_key,
            'signature' => $signature
        ];

        // إجراء طلب الرفع إلى Cloudinary
        $response = Cloudinary::uploadApi()->upload($data);

        // إرجاع رد الاستجابة من Cloudinary
        return response()->json($response);
    }

    public function getSignature()
    {
        // حساب الطابع الزمني بتنسيق timestamp
        $timestamp = round(microtime(true) * 1000);

        // توليد التوقيع
        $signature = Cloudinary::apiSignRequest(['timestamp' => $timestamp],"6H2jlDuaNl8ZP-g0oyJWRcB71BU");

        // إرجاع الطابع الزمني والتوقيع كاستجابة JSON
//        return $signature;
        return response()->json($signature);
    }


}
