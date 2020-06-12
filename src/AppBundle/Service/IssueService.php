<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 21/1/20
 * Time: 2:12 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IssueService extends BaseService
{
    /**
     * Function to validate and get all issues of consumer
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $issueID
     *
     * @return mixed
     */
    public function getIssues($authDetails, $queryParameter, $pathInfo, $issueID = null)
    {
        $returnData = array();
        try {
            //Get issue Repo
            $issueRepo = $this->entityManager->getRepository('AppBundle:Issues');

            //cheking valid query parameters
            $checkParams = array_diff(array_keys($queryParameter), GeneralConstants::PARAMS);
            if (count($checkParams) > 0) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //cheking valid data of query parameter
            $validation = $this->serviceContainer->get('vrscheduler.public_general_service');
            $validationCheck = $validation->validationCheck($queryParameter);
            if (!$validationCheck) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //check for limit option in query paramter
            (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

            //Setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //Getting issue Detail
            $issuesData = $issueRepo->getItems($authDetails['customerID'], $queryParameter, $issueID, $offset, $limit);

            //return 404 if resource not found
            if (empty($issuesData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = count($issueRepo->getItemsCount($authDetails['customerID'], $queryParameter, $issueID, $offset));

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($issuesData); $i++) {
                if (isset($issuesData[$i]['CreateDate'])) {
                    $issuesData[$i]['CreateDate'] = $issuesData[$i]['CreateDate']->format('Y-m-d');
                }

                if (isset($issuesData[$i]['ClosedDate'])) {
                    $issuesData[$i]['ClosedDate'] = $issuesData[$i]['ClosedDate']->format('Y-m-d');
                }

                //IssueType formating for response
                if (isset($issuesData[$i]['IssueType'])) {
                    switch ($issuesData[$i]['IssueType']) {
                        case 0:
                            $issuesData[$i]['IssueType'] = "Damage";
                            break;
                        case 1:
                            $issuesData[$i]['IssueType'] = "Maintenance";
                            break;
                        case 2:
                            $issuesData[$i]['IssueType'] = "Lost and Found";
                            break;
                        case 3:
                            $issuesData[$i]['IssueType'] = "Supply Flag";
                            break;
                        case -1:
                            $issuesData[$i]['IssueType'] = "None";
                            break;
                        default:
                            $issuesData[$i]['IssueType'] = null;
                    }
                }

                //StatusID formating for response
                if (isset($issuesData[$i]['StatusID'])) {
                    switch ($issuesData[$i]['StatusID']) {
                        case 0:
                            $issuesData[$i]['StatusID'] = "New";
                            break;
                        case 1:
                            $issuesData[$i]['StatusID'] = " In Progress";
                            break;
                        case 2:
                            $issuesData[$i]['StatusID'] = "On Hold";
                            break;
                        case 3:
                            $issuesData[$i]['StatusID'] = "Cataloged";
                            break;
                        default:
                            $issuesData[$i]['StatusID'] = null;
                    }
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $issuesData;
            $returnData['page_count'] = $totalPage;
            $returnData['page_size'] = $limit;
            $returnData['page'] = $offset;
            $returnData['total_items'] = $totalItems;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::ISSUES_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }
}