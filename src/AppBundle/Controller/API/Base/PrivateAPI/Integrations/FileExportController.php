<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 9/10/19
 * Time: 12:26 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Integrations;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class FileExportController extends FOSRestController
{
    /**
     * Get the qwc files in zip format
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 encode {""IntegrationID"":1}. Example eyJJbnRlZ3JhdGlvbklEIjoxfQ=="
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Downloads Zip file that contains the qwc XMLs"
     * )
     * @return array
     * @param Request $request
     * @Get("/qwc/file/export", name="vrs_qwc_file_export")
     */
    public function FileExport(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            $integrationID = $data['IntegrationID'];
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $integrationService = $this->container->get('vrscheduler.file_export');
            return $integrationService->DownloadQWC($integrationID,$customerID);

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}