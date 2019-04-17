<?php

namespace Encore\Stickies\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Stickies\Http\Models\StickyModel;

use Illuminate\View\Factory;

class StickiesController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Title')
            ->description('Descriptionasdasdasd')
            ->body(view('stickies::index'));
    }

    public function getAll() {
        $userId = Admin::user()->id;
        $err = [];
        $json = [];

        $stickyModel = new StickyModel;
        $stickies = $stickyModel::whereUserId($userId)->get();

        foreach ($stickies as $sticky) {
            $json[$sticky->name]['name'] = $sticky->name;
            $json[$sticky->name]['sticky'] = $sticky->sticky;
//            $json[$sticky->name]['path'] = $sticky->path;
        }

        if (!empty($err)) {
            return response()->json([
                'success' => false,
                'error' => $err,
            ]);
        } else {
            return response()->json([
                'success' =>  true,
                'data' =>  $json,
            ]);
        }

    }

    /**
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAll() {
        $stickies = request('stickies');

        if (empty($stickies)) {
            return;
        }

        $userId = Admin::user()->id;
        $err = [];

        foreach ($stickies as $sticky) {
            $name = key($sticky);
            $sticky = $sticky[$name];
            $stickyArr = json_decode($sticky,true);
            $path = $stickyArr['page'];

            // Make sure Sticky is in json
            // Set the variables
            $stickyModel = new StickyModel;
            $stickyModel->user_id = $userId;
            $stickyModel->name = $name;
            $stickyModel->path = $path;
            $stickyModel->sticky = $sticky;

            // insert to DB on duplicate - update
            $stickyModel::updateOrCreate(
                ['name' => $name, 'user_id' => $userId, 'path' => $path],
                ['sticky' => $sticky]
            );

            if (!$stickyModel){
                $err[] = $stickyModel->name;
            }
        }

        // Check for errors and handle appropriately.
        // $err takes the names of the failed stickies as array - the JS is handling the visualisation.
        if (!empty($err)) {
            return response()->json([
                'success' => false,
                'error' => $err,
            ]);
        } else {
            return response()->json([
                'success' =>  true,
            ]);
        }
    }

    /**
     * Deletes a sticky. Responsible also for multiple deleats as it is oncall function
     * and will be called for each sticky upon deleteAll action
     */
    public function delete() {
        $path = request('path');
        $userId = Admin::user()->id;
        $stickyID = request('id');
        $prefix = request('prefix');
        $name = str_replace($prefix, 'PostIt_', $stickyID);

        print_r($path);
        print_r($userId);
        print_r($name);
//      heck the current path and delete only stickies that are on the same path
        StickyModel::where('name', $name)->where('user_id', $userId)->where('path', $path)->delete();
        return response()->json([
            'success' =>  true,
        ]);
    }

}