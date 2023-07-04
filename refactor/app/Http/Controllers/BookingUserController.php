<?php

namespace DTApi\Http\Controllers;


use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller {
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        
        $response = $this->repository->getUsersJobsHistory($request->user_id, $request);
        return response($response);
        
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function accept(Request $request, $jobId)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJob($jobId, $user);

        return response($response);
    }

    // public function acceptJobWithId(Request $request)
    // {
    //     $data = $request->get('job_id');
    //     $user = $request->__authenticatedUser;

    //     $response = $this->repository->acceptJob($data, $user);

    //     return response($response);
    // }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->cancelJobAjax($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->endJob($data);

        return response($response);

    }
}