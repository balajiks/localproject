<?php

namespace Modules\Indexings\Http\Controllers\Base;

use App\Entities\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Indexings\Entities\Indexing;

abstract class SetupController extends Controller
{
    /**
     * Category model
     *
     * @var \App\Entities\Category
     */
    public $category;
    /**
     * Request instance
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    public function __construct(Category $category, Request $request)
    {
        $this->middleware(['auth', 'verified', '2fa', 'can:settings']);
        $this->request  = $request;
        $this->category = $category;
    }

    public function editStage($id = null)
    {
        $data['stage'] = Category::findOrFail($id);

        return view('indexings::modal.updateStage')->with($data);
    }

    public function updateStage($id = null)
    {
        $stage = $this->category->findOrFail($id);
        $stage->update($this->request->all());
        $data['message']  = langapp('changes_saved_successful');
        $data['redirect'] = url()->previous();

        return ajaxResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function showStages()
    {
        $data['stages'] = $this->category->whereModule('indexings')->orderBy('order', 'asc')->get();

        return view('indexings::modal.stages')->with($data);
    }

    public function showSources()
    {
        $data['sources'] = $this->category->whereModule('source')->orderBy('order', 'asc')->get();

        return view('indexings::modal.sources')->with($data);
    }

    public function saveSource()
    {
        if ($this->request->ajax()) {
            $this->request->validate(['name' => 'required']);
            $source = $this->category->create($this->request->all());
            $source->update(['order' => $this->category->whereModule('source')->count() + 1]);
            $html = view('indexings::_ajax.newSourceHtml', compact('source'))->render();

            return response()->json(
                [
                    'status' => 'success', 'html' => $html, 'message' => langapp('saved_successfully')],
                200
            );
        }
    }

    public function editSource($id = null)
    {
        $data['source'] = $this->category->findOrFail($id);

        return view('indexings::modal.updateSource')->with($data);
    }

    public function updateSource($id = null)
    {
        $source = $this->category->findOrFail($id);
        $source->update($this->request->all());
        $data['message']  = langapp('changes_saved_successful');
        $data['redirect'] = url()->previous();

        return ajaxResponse($data);
    }

    public function deleteSource($id = null)
    {
        $source = $this->category->findOrFail($id);
        if ($this->request->ajax()) {
            if ($source->delete()) {
                return response()->json(
                    [
                        'status' => 'success', 'message' => langapp('deleted_successfully')],
                    200
                );
            }

            return response()->json(['status' => 'errors', 'message' => 'something went wrong'], 401);
        }
    }

    public function saveStage()
    {
        if ($this->request->ajax()) {
            $this->request->validate(['name' => 'required']);
            $stage = $this->category->create($this->request->all());
            $stage->update(['order' => $this->category->whereModule('indexings')->count() + 1]);
            $html = view('indexings::_ajax.newStageHtml', compact('stage'))->render();

            return response()->json(['status' => 'success', 'html' => $html, 'message' => langapp('saved_successfully')], 200);
        }
    }

    public function moveStage()
    {
        $target_id = Category::whereName(humanize($this->request->target))->first()->id;
        $indexing      = Indexing::findOrFail($this->request->id);
        $indexing->update(['stage_id' => $target_id]);

        return langapp(
            'indexing_stage_changed',
            [
                'title' => $indexing->name,
                'stage' => humanize($this->request->target),
            ]
        );
        exit;
    }

    public function deleteStage($id = null)
    {
        $stage = $this->category->findOrFail($id);
        if ($this->request->ajax()) {
            if ($stage->delete()) {
                \Modules\Indexings\Entities\Indexing::where('stage_id', $stage->id)->update(['stage_id' => get_option('default_indexing_stage')]);

                return response()->json(['status' => 'success', 'message' => langapp('deleted_successfully')], 200);
            }

            return response()->json(['status' => 'errors', 'message' => 'something went wrong'], 401);
        }
    }

    public function ajaxOrderStages()
    {
        if ($this->request->ajax()) {
            foreach ($this->request->sortedList as $key => $item) {
                foreach ($item as $val) {
                    $id = str_replace('stage-', '', $val);
                    $this->category->findOrFail($id)->update(['order' => $key]);
                }
            }
            return response()->json(
                ['status' => 'success', 'message' => langapp('changes_saved_successful')],
                200
            );
        }
    }
}
