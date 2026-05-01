<?php

namespace App\Http\Controllers;

use App\Models\DiningArea;
use App\Models\Table;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TableController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant,404);
        $tables = Table::with('diningArea')->where('restaurant_id',$restaurant->id)->orderBy('sort_order')->get();
        return view('tables.index', ['tables'=>$tables]);
    }

    public function create(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant,404);
        $areas = DiningArea::where('restaurant_id',$restaurant->id)->where('is_active',true)->orderBy('sort_order')->get();
        return view('tables.form', ['table'=>new Table(['status'=>'free','seats'=>2]), 'areas'=>$areas, 'statuses'=>Table::STATUSES]);
    }

    public function store(Request $request): RedirectResponse
    { $restaurant=$this->currentRestaurant($request); abort_unless($restaurant,404); $data=$this->validateTable($request,$restaurant->id); $data['restaurant_id']=$restaurant->id; Table::create($data); return redirect()->route('tables.index')->with('status','Tavolo creato.'); }

    public function edit(Request $request, Table $table): View
    { $restaurant=$this->currentRestaurant($request); abort_unless($restaurant && $table->restaurant_id===$restaurant->id,404); $areas=DiningArea::where('restaurant_id',$restaurant->id)->orderBy('sort_order')->get(); return view('tables.form', ['table'=>$table,'areas'=>$areas,'statuses'=>Table::STATUSES]); }

    public function update(Request $request, Table $table): RedirectResponse
    { $restaurant=$this->currentRestaurant($request); abort_unless($restaurant && $table->restaurant_id===$restaurant->id,404); $table->update($this->validateTable($request,$restaurant->id)); return redirect()->route('tables.index')->with('status','Tavolo aggiornato.'); }

    public function destroy(Request $request, Table $table): RedirectResponse
    { $restaurant=$this->currentRestaurant($request); abort_unless($restaurant && $table->restaurant_id===$restaurant->id,404); $table->delete(); return back()->with('status','Tavolo eliminato.'); }

    public function quickStatus(Request $request, Table $table): RedirectResponse
    { $restaurant=$this->currentRestaurant($request); abort_unless($restaurant && $table->restaurant_id===$restaurant->id,404); $request->validate(['status'=>['required',Rule::in(Table::STATUSES)]]); $table->update(['status'=>$request->string('status')]); return back()->with('status','Stato tavolo aggiornato.'); }


    public function roomDashboard(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant,404);
        $tables = Table::with('diningArea')->where('restaurant_id',$restaurant->id)->orderBy('sort_order')->get();
        return view('room.dashboard', ['tables'=>$tables,'today'=>now()->toDateString(),'occupied'=>$tables->where('status','occupied')->count(),'total'=>$tables->count()]);
    }

    private function validateTable(Request $request, int $restaurantId): array
    { return $request->validate(['name'=>'required|string|max:255','dining_area_id'=>['required',Rule::exists('dining_areas','id')->where('restaurant_id',$restaurantId)],'seats'=>'required|integer|min:1|max:50','status'=>['required',Rule::in(Table::STATUSES)],'pos_x'=>'nullable|numeric','pos_y'=>'nullable|numeric','sort_order'=>'nullable|integer|min:0']); }
}
