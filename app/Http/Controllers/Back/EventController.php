<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventFromRequest;
use App\Http\Requests\EventIdFormRequest;
use App\Http\Requests\SearchFromRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    // Afficher tous les événements
    public function index(SearchFromRequest $request){
        $search=array();
        $search['status']=$request->input('status');
        $search['from']=$request->input('from');
        $search['to']=$request->input('to ');

        return Inertia::render('Event/Index',[
            'events'=>Event::filter($search)->latest()->paginate(8),
        ]);
    }
    // Enregister un événement
    public function store(EventFromRequest $request){
        $event=new Event();
        $event->title=$request->input('title');
        $event->start=$request->input('start');
        $event->end=$request->input('end');
        $event->status=$request->input('status');
        $event->save();

        return redirect()->back();
    }
    // Modifier un événement
    public function update(EventFromRequest $request){
        $event=Event::query()->find($request->input('id'));
        $event->title=$request->input('title');
        $event->start=$request->input('start');
        $event->end=$request->input('end');
        $event->status=$request->input('status');
        $event->save();
        return redirect()->back();
    }
    // Supprimer un événement
    public function destroy(Request $request)
    {
        $event=Event::query()->find($request->input('id'));
        $event->forceDelete();

        return redirect()->back();
    }
    // Masqué un événement
    public function mask(Request $request){
        $event=Event::query()->find($request->input('id'));
        $event->delete();
        return redirect()->back();
    }
    public function homePage(){
        return Inertia::render('Dashboard');
    }
}
