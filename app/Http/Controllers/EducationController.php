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
            return response()->json(['data'=>$classroom]);
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
            return response()->json($subjects);
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

        return response()->json($titles);
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



    ///////////////////////////////////////////////////////////////////////////cloudinar

//        try {
//            Configuration::instance([
//                'cloud' => [
//                    'cloud_name' => config('services.cloudinary.cloud_name'),
//                    'api_key' => config('services.cloudinary.api_key'),
//                    'api_secret' => config('services.cloudinary.api_secret')
//                ]
//            ]);
    public function cloudinary()
    {


        try {
            $apiKey = config('cloudinary.api_key');
            $apiSecret = config('cloudinary.api_secret');

            $client = new Client();
            $response = $client->post("https://api.cloudinary.com/v1_1/ARWA/generate_auth_token", [
                'form_params' => [
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                    'expires_at' => now()->addHours(1)->timestamp, // تحديد صلاحية التوكن
                    'allowed_formats' => ['jpg', 'jpeg', 'png', 'gif'], // الأذونات المسموح بها للتوكن
                    // يمكنك إضافة المزيد من الخيارات هنا
                ]
            ]);

            // التحقق من صحة الاستجابة
            if (!empty($response)) {
                // قراءة جسم الاستجابة كنص
                $token = $response->getBody()->getContents();

                return response()->json(['token' => $token]);
            } else {
                return response()->json(['error' => 'Failed to get token'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function uploadImage(Request $request)
    {
        try {

            $image = $request->file('image');
            $uploadedFileUrl = Cloudinary::uploadFile($request->file('image')->getRealPath())->getSecurePath();

            return response()->json(['token' => $uploadedFileUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function createAccessToken()
    {

        $folderId = 'c78761149b09548abd7ee4d7201cf19736';
        $folderAcl = "*/folders/$folderId/*";
        $authToken = new AuthToken([
            'key' => '952944418364724',
            'duration' => 100,
            'acl' => $folderAcl
        ]);

        $token = $authToken->generate();
        return response()->json(['token' => $token]);
    }



    public function uploadImageUsingToken1(Request $request)
    {
        $image = $request->file('image');

        // الحصول على التوكن
        $token = $this->createAccessToken();

        // رفع الصورة إلى Cloudinary باستخدام التوكن
        $response = Http::attach(
            'file', file_get_contents($image), $image->getClientOriginalName()
        )->post('https://api.cloudinary.com/v1_1/dftvov92g/image/upload', [
            'upload_preset' => 'ml_default',
            'folder' => 'arwa2',
            'upload_token' => $token,
        ]);

        // يمكنك تنفيذ أي إجراءات إضافية هنا، مثل حفظ معرّف الصورة في قاعدة البيانات

        // إرجاع رد الاستجابة بمعلومات الصورة المرفوعة

        // إرجاع رابط الصورة المرفوعة
        return response()->json(['image_url' => $response]);
    }



    public function uploadImageToken(Request $request)
    {
        // Generate timestamp
        $timestamp = time();

        // Upload parameters
        $params = [
            'timestamp' => $timestamp,
            'upload_preset' => 'ml_default', // Optional: Include your upload preset name
            // Add other upload parameters as needed
        ];

        // Generate the signature
        $signature = $this->generateUploadSignature($params);

        // Include the signature in the upload parameters
        $params['signature'] = $signature;

        // Get the uploaded file
        $file = $request->file('image');

        // Perform the upload using the Cloudinary SDK
        $result = Cloudinary::uploadApi()->upload($file->getRealPath(), $params); // Pass the real path of the file

        return $result;
    }

    private function generateUploadSignature($params)
    {
        // Construct the string to sign using the upload parameters and your Cloudinary API secret
        $stringToSign = http_build_query($params) . '6H2jlDuaNl8ZP-g0oyJWRcB71BU';

        // Generate the signature by hashing the string to sign
        $signature = hash('sha256', $stringToSign);

        return $signature;
    }




}

//
//CLOUDINARY_CLOUD_NAME=dftvov92g
//CLOUDINARY_API_KEY=952944418364724
//CLOUDINARY_API_SECRET=6H2jlDuaNl8ZP-g0oyJWRcB71BU
//CLOUDINARY_URL=cloudinary://952944418364724:6H2jlDuaNl8ZP-g0oyJWRcB71BU@dftvov92g
