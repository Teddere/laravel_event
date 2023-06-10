<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventFromRequest;
use App\Http\Requests\EventIdFormRequest;
use App\Http\Requests\SearchFromRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;

class FrontController extends Controller
{
    // Afficher tous les événements
    public function index(SearchFromRequest $request)
    {
        $from=$request->input('from');
        $to=$request->input('to');
        $status=$request->input('status');
        $events=Event::query()->when($from ,function ($query) use($from){
            $query->where('start','>=',"{$from}");
        })->when($to,function ($query) use ($to){
            $query->where('end','<',"{$to}");
        })->when($status, function ($query) use($status){
            $query->where('status',"{$status}");
        })->latest()->paginate(6);
        return Inertia::render('Front/Index',[
            'events'=>$events,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    // Enregister un événement
    public function store(EventFromRequest $request)
    {
        $event=new Event();
        $event->title=$request->input('title');
        $event->start=$request->input('start');
        $event->end=$request->input('end');
        $event->status=$request->input('status');
        $event->save();
        return  redirect()->back();
    }

    // Modifier un événement
    public function update(EventFromRequest $request)
    {
        $event=Event::find($request->input('id'));
        $event->title=$request->input('title');
        $event->start=$request->input('start');
        $event->end=$request->input('end');
        $event->status=$request->input('status');
        $event->save();
        return redirect()->back();
    }

    // Masquer un événement
    public function destroy(EventIdFormRequest $request)
    {
        $event=Event::find($request->input('id'));
        $event->delete();

        return redirect()->back();
    }
}
