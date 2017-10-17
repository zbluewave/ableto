<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/15/17
 * Time: 7:30 PM
 */


namespace App\Http\Controllers;

use App\Packages\Human\HumanElasticGateway;
use App\Packages\Human\HumanElasticSearchRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Packages\Human\HumanGeneration;

class HumanSearchController extends BaseController
{
    private $humanGeneration;
    private $humanElasticGateway;

    public function __construct(
        HumanGeneration $humanGeneration,
        HumanElasticGateway $humanElasticGateway
    ) {
        $this->humanGeneration = $humanGeneration;
        $this->humanElasticGateway = $humanElasticGateway;
    }

    /**
     * @SWG\Get(
     *     path="/api/human/search",
     *     description="Returns a user based on a single ID, if the user does not have access to the pet",
     *     operationId="search hummans",
     *     @SWG\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="characters to search in first name",
     *         required=false,
     *         type="string"
     *     ),
     *    @SWG\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="characters to search in last name",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="age",
     *         in="query",
     *         description="age of the person to search",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Parameter(
     *         name="gender",
     *         in="query",
     *         description="gender of the person to search",
     *         required=false,
     *         type="string",
     *         enum={"male", "female"}
     *     ),
     *     @SWG\Parameter(
     *         name="after_id",
     *         in="query",
     *         description="for pagination return results after this id",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     *     @SWG\Response(
     *         response=200,
     *         description="Search response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Property(property="total", type="integer", description="total record found"),
     *             @SWG\Property(property="data_size", type="integer", description="number of records returned"),
     *             @SWG\Property(property="last_id", type="integer", description="use this id for next page search"),
     *             @SWG\Property(
     *                 property="data",
     *                 type="array",
     *                 @SWG\Items(title="data",  ref="#/definitions/human")
     *             )
     *         ),
     *     )
     * )
     */
    public function searchHuman()
    {
        //$this->humanGeneration->persistHumans();
        $searchRequest = new HumanElasticSearchRequest();
        $searchRequest->addFirstNameKeywords(request()->input('first_name', ''));
        $searchRequest->addLastNameKeywords(request()->input('last_name', ''));
        $searchRequest->addAge(request()->input('age', -1));
        $searchRequest->addGender(request()->input('gender', ''));
        $searchRequest->addSearchAfter(request()->input('after_id', -1));
        return response()->json($this->humanElasticGateway->searchHumans($searchRequest));
    }
}
