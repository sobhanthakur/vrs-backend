<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 8/5/20
 * Time: 12:29 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Post;
class ManageController extends FOSRestController
{
    /**
     * Submits issue form
     * @SWG\Tag(name="Manage Tab")
     * @Post("/issue", name="vrs_pwa_issue_post")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="FormServiceID",
     *              type="integer",
     *              example="1801 // Note: This PropertyID should come from the form's dropdown"
     *         ),
     *         @SWG\Property(
     *              property="PropertyID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="Images",
     *              example={
     *                  {
     *                      "Image" : "Image1"
     *                  },
     *                  {
     *                      "Image" : "Image2"
     *                  }
     *              }
     *      ),
     *         @SWG\Property(
     *              property="Issue",
     *              type="string",
     *              example="Issues"
     *     ),
     *         @SWG\Property(
     *              property="IssueType",
     *              type="integer",
     *              example="1 // 0=Damage,1=Maintenance,2=Lost and Found"
     *     ),
     *         @SWG\Property(
     *              property="Urgent",
     *              type="boolean",
     *              example="1/0"
     *     ),
     *         @SWG\Property(
     *              property="IssueDescription",
     *              type="string",
     *              example="Some Description"
     *     )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Submits the issue form",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function PostIssue(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_service');
            $content = json_decode($request->getContent(),true);

            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $manageService->SubmitIssue($servicerID,$content);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Submits issue form
     * @SWG\Tag(name="Manage Tab")
     * @Post("/manage/save", name="vrs_pwa_manage_save")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=1801
     *         ),
     *     @SWG\Property(
     *              property="TaskNote",
     *              type="string",
     *              example="Some Task Note"
     *         ),
     *     @SWG\Property(
     *              property="NoteToOwner",
     *              type="string",
     *              example="Some note to owner"
     *         ),
     *     @SWG\Property(
     *              property="CheckListDetails",
     *              type="string",
     *              example={
     *              {
                        "ChecklistTypeID" : 10,
                        "ChecklistItemID" : 2995,
                        "Input" : {
     *                      {
                                "TaskToChecklistItemID" : 1716941,
                                "Checked" : 0,
                                "ImageUploaded" : "",
                                "OptionSelected" : "1",
                                "EnteredValue" : "",
                                "EnteredValueAmount" : ""
                            }
     *                     }
                        }
     *              }
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Submits the issue form",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function SaveManage(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_save');
            $content = json_decode($request->getContent(),true);

            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }

            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $manageService->SaveManageDetails($servicerID,$content);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Submits issue form
     * @SWG\Tag(name="Manage Tab")
     * @Post("/manage/complete", name="vrs_pwa_manage_submit")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=1801
     *         ),
     *     @SWG\Property(
     *              property="DateTime",
     *              type="string",
     *              example="2020-06-06 08:30:00"
     *         ),
     *     @SWG\Property(
     *              property="TaskNote",
     *              type="string",
     *              example="Some Task Note"
     *         ),
     *     @SWG\Property(
     *              property="NoteToOwner",
     *              type="string",
     *              example="Some note to owner"
     *         ),
     *     @SWG\Property(
     *              property="CheckListDetails",
     *              type="string",
     *              example={
     *              {
    "ChecklistTypeID" : 10,
    "ChecklistItemID" : 2995,
    "Input" : {
     *                      {
    "TaskToChecklistItemID" : 1716941,
    "Checked" : 0,
    "ImageUploaded" : "",
    "OptionSelected" : "1",
    "EnteredValue" : "",
    "EnteredValueAmount" : ""
    }
     *                     }
    }
     *              }
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Submits the issue form",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function SubmitManage(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_submit');
            $content = json_decode($request->getContent(),true);

            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }
            
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            $mobileHeaders = $request->attributes->get(GeneralConstants::MOBILE_HEADERS);
            return $manageService->SubmitManageForm($servicerID,$content,$mobileHeaders);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Uploads Image to corresponding folder
     * @SWG\Tag(name="Manage Tab")
     * @Post("/issue/upload/image", name="vrs_pwa_upload_image_post")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="CustomerID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="Image",
     *              type="file",
     *              example="Image File"
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Uploads Image to corresponding folder",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function UploadImage(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_service');
            return $manageService->UploadImage($request);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Submits issue form
     * @SWG\Tag(name="Manage Tab")
     * @Post("/issue/vendorandowner", name="vrs_pwa_issue_post_vendor_owner")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="Images",
     *              example={
     *                  {
     *                      "Image" : "Image1"
     *                  },
     *                  {
     *                      "Image" : "Image2"
     *                  }
     *              }
     *      ),
     *         @SWG\Property(
     *              property="Issue",
     *              type="string",
     *              example="Issues"
     *     ),
     *         @SWG\Property(
     *              property="Urgent",
     *              type="boolean",
     *              example="1/0"
     *     ),
     *         @SWG\Property(
     *              property="IssueDescription",
     *              type="string",
     *              example="Some Description"
     *     )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Submits the issue form",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function IssueFormForVendorAndOwner(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_service');
            $content = json_decode($request->getContent(),true);

            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE];
            if (array_key_exists(GeneralConstants::VENDORID,$servicerID)) {
                $servicerID = $servicerID[GeneralConstants::VENDORID];
                $owner = null;
            } else {
                $servicerID = $servicerID[GeneralConstants::OWNERID];
                $owner = true;
            }

            return $manageService->SubmitIssue($servicerID,$content,$owner);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Deletes Checklist
     * @SWG\Tag(name="Manage Tab")
     * @Rest\Delete("/manage/delete", name="vrs_pwa_manage_delete")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="TaskToChecklistItemID",
     *              type="integer",
     *              example=1801
     *         )
     *      )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Submits the issue form",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function DeleteManageChecklist(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $manageService = $this->container->get('vrscheduler.manage_save');
            $content = json_decode($request->getContent(),true);

            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }

            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $manageService->RemoveChecklist($servicerID,$content);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}