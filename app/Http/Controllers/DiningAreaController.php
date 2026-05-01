<?php

namespace App\Http\Controllers;

use App\Models\DiningArea;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiningAreaController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant, 404);
        $areas = DiningArea::where('restaurant_id', $restaurant->id)->orderBy('sort_order')->get();
        return view('dining-areas.index', compact('areas'));
    }

    public function create(): View { return view('dining-areas.form', ['area' => new DiningArea(['is_active' => true])]); }
    public function edit(Request $request, DiningArea $diningArea): View { $r=$this->currentRestaurant($request); abort_unless($r && $diningArea->restaurant_id===$r->id,404); return view('dining-areas.form',['area'=>$diningArea]); }

    public function store(Request $request): RedirectResponse
    {
        $r = $this->currentRestaurant($request); abort_unless($r, 404);
        $data = $request->validate(['name'=>'required|string|max:255','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['restaurant_id']=$r->id; $data['is_active']=$request->boolean('is_active');
        DiningArea::create($data);
        return redirect()->route('dining-areas.index')->with('status','Sala creata.');
    }

    public function update(Request $request, DiningArea $diningArea): RedirectResponse
    {
        $r = $this->currentRestaurant($request); abort_unless($r && $diningArea->restaurant_id===$r->id, 404);
        $data = $request->validate(['name'=>'required|string|max:255','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['is_active']=$request->boolean('is_active'); $diningArea->update($data);
        return redirect()->route('dining-areas.index')->with('status','Sala aggiornata.');
    }

    public function destroy(Request $request, DiningArea $diningArea): RedirectResponse
    {
        $r = $this->currentRestaurant($request); abort_unless($r && $diningArea->restaurant_id===$r->id,404);
        $diningArea->delete(); return back()->with('status','Sala eliminata.');
    }
}
