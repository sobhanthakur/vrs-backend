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
     * @param $regionGroupsID
     *
     * @return mixed
     */
    public function getIssues($authDetails, $queryParameter, $pathInfo, $issueID = null)
    {
        $returnData = array();
        try {
            //Get region Repo
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

            //Setting offset
            (isset($queryParameter['startingafter']) ? $offset = $queryParameter['startingafter'] : $offset = 1);

            //Getting issue Detail
            $issuesData = $issueRepo->fetchIssues($authDetails['customerID'], $queryParameter, $issueID, $offset);

            //checking if more records are there to fetch from db
            $hasMoreDate = count($issueRepo->fetchIssues($authDetails['customerID'], $queryParameter, $issueID, $offset + 1));

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($issuesData); $i++) {
                if (isset($issuesData[$i]['CreateDate'])) {
                    $issuesData[$i]['ClosedDate'] = $issuesData[$i]['ClosedDate']->format('Ymd');
                    $issuesData[$i]['CreateDate'] = $issuesData[$i]['CreateDate']->format('Ymd');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            $hasMoreDate != 0 ? $returnData['has_more'] = true : $returnData['has_more'] = false;
            $returnData['data'] = $issuesData;

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