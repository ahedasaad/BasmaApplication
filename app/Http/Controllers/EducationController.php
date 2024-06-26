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

    public function __construct(EducationService $educationService,CloudinaryService $cloudinaryService)
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
            return response()->json(['data'=>$explanations]);

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
    public function rejectedExplanation($explanation)
    {
        try {
            $explanation = $this->educationService->rejectedExplanation($explanation);

            return response()->json($explanation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    //CLOUDINARY_UPLOAD_PRESET=ml_default
    //CLOUDINARY_CLOUD_NAME=dftvov92g
    //CLOUDINARY_API_KEY=464627112571591
    //CLOUDINARY_API_SECRET=8JFiG3bxjkOVnGaN-oRPKCTObto
    //CLOUDINARY_URL=cloudinary://464627112571591:8JFiG3bxjkOVnGaN-oRPKCTObto@dftvov92g

    public function generateSignature1()
    {
        try {
            $signatureData = $this->cloudinaryService->generateSignature();

            return response()->json(['$signatureData'=>$signatureData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function saveExplanationUrl(Request $request, $explanatId)
    {
        try {// استخراج الرابط من الريكوس
        $url = $request->input('url');
            $explanatId=$this->cloudinaryService->saveExplanationUrl($explanatId, $url);
        return response()->json(['message' => 'Explanation URL saved successfully','explanatId'=>$explanatId]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    ////////////just for teasting
    public function uploadToCloudinary1(Request $request)
    {
        try {
            // تحقق من أن الطلب يحتوي على ملف مرفق
            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'No image file found in the request'], 400);
            }

            $uploadResponse = $this->cloudinaryService->uploadToCloudinary1($request);

            return response()->json(['uploadResponse' => $uploadResponse]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generateSignature()
    {
        $timestamp = time();
        $paramsToSign = [
            'timestamp' => $timestamp,
            'upload_preset' => 'ml_default', // يجب أن تكون قد أنشأت مسبقًا في إعدادات Cloudinary
        ];

        $apiSecret = env('CLOUDINARY_API_SECRET');
        $signature = \Cloudinary\Api\ApiUtils::signParameters($paramsToSign, $apiSecret);

        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => env('CLOUDINARY_API_KEY'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'upload_preset' => 'ml_default',
        ]);
    }


    public function uploadToCloudinary(Request $request)
    {
        // احصل على التوقيع الرقمي من المشروع الآخر
        $signatureResponse = $this->generateSignature();
        $signatureData = $signatureResponse->getData(true); // تحويل الاستجابة إلى مصفوفة


        $file = $request->file('image');

        // إنشاء FormData وإضافة البيانات اللازمة
        $formData = [
            'file' => fopen($file->getPathname(), 'r'),
            'timestamp' => $signatureData['timestamp'],
            'api_key' => $signatureData['api_key'],
            'signature' => $signatureData['signature'],
            'upload_preset' => $signatureData['upload_preset'],
        ];

        // إرسال طلب الرفع إلى Cloudinary مع زيادة مهلة الاتصال
        $uploadResponse = Http::timeout(1800) // تحديد المهلة بـ 60 ثانية
        ->asMultipart()
            ->post("https://api.cloudinary.com/v1_1/{$signatureData['cloud_name']}/image/upload", $formData);

        return response()->json($uploadResponse->json());
    }

    public function uploadVideoToCloudinary(Request $request)
    {
        // احصل على التوقيع الرقمي من المشروع الآخر
        $signatureResponse = $this->generateSignature();
        $signatureData = $signatureResponse->getData(true); // تحويل الاستجابة إلى مصفوفة

        $file = $request->file('video'); // استخدام 'video' بدلاً من 'image'

        // إنشاء FormData وإضافة البيانات اللازمة
        $formData = [
            'file' => fopen($file->getPathname(), 'r'),
            'timestamp' => $signatureData['timestamp'],
            'api_key' => $signatureData['api_key'],
            'signature' => $signatureData['signature'],
            'upload_preset' => $signatureData['upload_preset'],
        ];

        // إرسال طلب الرفع إلى Cloudinary مع زيادة مهلة الاتصال
        $uploadResponse = Http::timeout(1800) // تحديد المهلة بـ 30 دقيقة
        ->asMultipart()
            ->post("https://api.cloudinary.com/v1_1/{$signatureData['cloud_name']}/video/upload", $formData);

        return response()->json($uploadResponse->json());
    }

    public function fetchVideoFromCloudinary()
    {
        // احصل على التوقيع الرقمي من المشروع الآخر
        $signatureResponse = $this->generateSignature();
        $signatureData = $signatureResponse->getData(true); // تحويل الاستجابة إلى مصفوفة

//        $publicId = $request->input('public_id'); // احصل على public_id من الطلب
        $publicId='phpB3D5_zmy4fv';
        // إعداد المعلمات اللازمة للطلب
        $params = [
            'timestamp' => $signatureData['timestamp'],
            'public_id' => 'phpB3D5_zmy4fv',
            'api_key' => $signatureData['api_key'],
            'signature' => $signatureData['signature'],
        ];

        // إرسال طلب الجلب إلى Cloudinary
        $fetchResponse = Http::timeout(150) // تحديد المهلة بـ 30 دقيقة
        ->get("https://res.cloudinary.com/{$signatureData['cloud_name']}/video/upload/{$publicId}", $params);
        if ($fetchResponse->failed()) {
            return response()->json(['error' => 'Failed to fetch video from Cloudinary'], 500);
        }
        return response()->json($fetchResponse);
    }
    public function fetchVideoFromCloudinary1()
    {
        // جلب الفيديو من Cloudinary باستخدام معرف الفيديو
        $fetchResponse = Cloudinary::video('phpB3D5_zmy4fv');

        // يمكنك تنفيذ أي عمليات إضافية هنا مع الفيديو
        // على سبيل المثال، يمكنك إضافة مرشحات أو تطبيق تعديلات

        // إرجاع الفيديو
        return response()->json($fetchResponse);;
    }

}
