<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ForumRequest;
use App\Http\Resources\ForumResource;
use App\Repository\ForumCommentRepository;
use App\Repository\ForumRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    use ResponseTrait;
    private $forumRepository;
    private $forumCommentRepository;
    public function __construct(ForumRepository $forumRepository, ForumCommentRepository $forumCommentRepository)
    {
        $this->forumRepository = $forumRepository;
        $this->forumCommentRepository = $forumCommentRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search ?? '';
        return ForumResource::collection($this->forumRepository->getAll('approved', $search));
    }

    public function pendingList(Request $request)
    {
        if (!$request->user()->is_admin) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 422);
        }
        return ForumResource::collection($this->forumRepository->getAll('unapproved', ''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ForumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForumRequest $request)
    {
        $user = Auth::user();
        $data = array_merge($request->validated(), ['user_id' => $user->id, 'is_approved' => $user->is_admin]);
        $response = $this->forumRepository->create($data);

        return new ForumResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ForumResource($this->forumRepository->get($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 422);
        }
        $response = $this->forumRepository->update($id, ['is_approved' => $request->is_approved ?? 0]);
        return isset($response['error']) ?  $this->jsonResponse(['message' => $response['error']], 400) : new ForumResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->forumRepository->delete(Auth::user()->id, $id);

        return isset($response['error']) ?  $this->jsonResponse(['message' => $response['error']], 400) : $this->jsonResponse(['data' => $response['success']], 200);
    }

    public function postForumComment(CommentRequest $request, $id)
    {
        $data = array_merge($request->validated(), ['forum_id' => $id, 'user_id' => Auth::user()->id]);
        $response = $this->forumCommentRepository->create($data);
        return $this->jsonResponse(['data' => $response], 201);
    }
}
