<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingLibrary\TrainingLibraryRequest;
use App\Http\Requests\TrainingLibrary\TrainingLibraryTopicRequest;
use App\Http\Requests\TrainingLibrary\FetchTrainingLibraryRequest;
use App\Http\Requests\TrainingLibrary\FetchTrainingLibraryTopicRequest;
use App\Http\Resources\TrainingLibrary\TrainingLibraryResource;
use App\Http\Resources\TrainingLibrary\TrainingLibraryTopicResource;
use Illuminate\Http\Request;

class TrainingLibraryController extends Controller
{
    public function saveTrainingLibrary(TrainingLibraryRequest $request) {
        return $request->saveTrainingLibrary();
    }

    public function saveTrainingLibraryTopic(TrainingLibraryTopicRequest $request) {
        return $request->saveTrainingLibraryTopic();
    }

    public function toggleFavorite(TrainingLibraryRequest $request, $id) {
        return $request->toggleFavorite($id);
    }

    public function toggleDisplay(TrainingLibraryTopicRequest $request, $id) {
        return $request->toggleDisplay($id);
    }

    public function markAsCompleted(TrainingLibraryRequest $request, $id) {
        return $request->markAsCompleted($id);
    }

    public function getFavoriteVideos(FetchTrainingLibraryRequest $request) {

        $page = $request->query('page', 1); // Default to 1
        $perPage = $request->query('per_page', 6); // Default to 6

        return TrainingLibraryResource::collection($request->getFavoriteVideos($page, $perPage));
    }

    public function getCompletedVideos(FetchTrainingLibraryRequest $request) {

        $page = $request->query('page', 1); // Default to 1
        $perPage = $request->query('per_page', 6); // Default to 6

        return TrainingLibraryResource::collection($request->getCompletedVideos($page, $perPage));
    }

    public function updateTrainingLibrary(TrainingLibraryRequest $request, $id) {
        return $request->updateTrainingLibrary($id);
    }

    public function updateTrainingLibraryTopic(TrainingLibraryTopicRequest $request, $id) {
        return $request->updateTrainingLibraryTopic($id);
    }

    public function getTrainingLibraries(FetchTrainingLibraryRequest $request) {

        $page = $request->query('page', 1); // Default to 1
        $perPage = $request->query('per_page', 6); // Default to 6
        $tab = $request->query('tab');

        return TrainingLibraryResource::collection($request->getTrainingLibraries($page, $perPage, $tab));
    }

    public function getTrainingLibraryTopics(FetchTrainingLibraryTopicRequest $request) {
        return TrainingLibraryTopicResource::collection($request->getTrainingLibraryTopics());
    }

    public function getTrainingLibraryTopicsAll(FetchTrainingLibraryTopicRequest $request) {
        return TrainingLibraryTopicResource::collection($request->getTrainingLibraryTopicsAll());
    }

    public function getTrainingLibrariesWith(FetchTrainingLibraryRequest $request) {

        $tab = $request->query('tab');
        $filter = $request->query('filter');
        $page = $request->query('page', 1); // Default to 1
        $perPage = $request->query('per_page', 6); // Default to 6

        return TrainingLibraryResource::collection($filter == 'all' ? $request->getTrainingLibraries($page, $perPage, $tab) : $request->getTrainingLibrariesWith($filter, $page, $perPage, $tab));
    }

    public function deleteTrainingLibrary(TrainingLibraryRequest $request, $id) {
        return $request->deleteTrainingLibrary($id);
    }

    public function deleteTrainingLibraryTopic(TrainingLibraryTopicRequest $request, $id) {
        return $request->deleteTrainingLibraryTopic($id);
    }
}
