<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveArticleDraftRequest;
use App\Http\Requests\PublishArticleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogArticleController extends Controller
{
    /**
     * Create a new BlogArticleController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'articles']);
    }

    /*
     * Get all newsletters send from this server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function articles()
    {
        return DB::table('articles')->where('draft', false)->orderBy('id', 'desc')->get();
    }

    /*
     * Get the draft from the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getdraft()
    {
        return DB::table('articles')->where('draft', true)->get();
    }

    public function howManyDrafts() {
        return DB::table('articles')->where('draft', true)->count();
    }

    public function deleteDraft() {
        $drafts = $this->howManyDrafts();
        while($drafts) {
            DB::table('articles')->where('draft', true)->delete();
            $drafts = $this->howManyDrafts();
        }
        return $this->deleteDraftSuccessResponse();
    }

    /*
     * Save the draft of a new newsletter
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveArticlesDraft(SaveArticleDraftRequest $request)
    {
        $drafts = $this->howManyDrafts();
        if($drafts == 1) {
            DB::table('articles')->where('draft', true)->update(array(
                'content' => $request->content,
                'draft' => $request->draft
            ));
        } else if($drafts == 0) {
            DB::table('articles')->insert(array(
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'draft' => $request->draft
            ));
        } else {
            return $this->saveDraftFailedResponse();
        }
        return $this->saveDraftSuccessResponse();
    }

    /*
     * Send a new newsletter to all subscribers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishArticle(PublishArticleRequest $request)
    {
        $drafts = $this->howManyDrafts();
        if($request->draft == true) {
            return $this->sendNewsletterFailedResponse();
        } else {
            if($this->howManyDrafts() > 0) {
                $this->deleteDraft();
            }
            DB::table('articles')->insert(array(
                'content' => $request->content,
                'draft' => false,
                'description' => $request->description,
                'title' => $request->title
            ));
            return $this->sendNewsletterSuccessResponse();
        }
    }

    public function deleteDraftSuccessResponse()
    {
        return response()->json([
            'success' => 'Cancellazione avvenuta con successo'
        ], Response::HTTP_OK);
    }

    public function deleteDraftFailedResponse()
    {
        return response()->json([
            'error' => 'C\'è stato un problema durante la cancellazione della bozza.'
        ], Response::HTTP_FORBIDDEN);
    }

    public function saveDraftSuccessResponse()
    {
        return response()->json([
            'success' => 'Salvataggio avvenuto con successo'
        ], Response::HTTP_OK);
    }

    public function saveDraftFailedResponse()
    {
        return response()->json([
            'error' => 'C\'è stato un problema durante il salvataggio della bozza.'
        ], Response::HTTP_FORBIDDEN);
    }

    public function sendNewsletterSuccessResponse()
    {
        return response()->json([
            'success' => 'Pubblicazione articolo avvenuta con successo'
        ], Response::HTTP_OK);
    }

    public function sendNewsletterFailedResponse()
    {
        return response()->json([
            'error' => 'C\'è stato un problema durante la pubblicazione dell\'articolo.'
        ], Response::HTTP_FORBIDDEN);
    }

}
