<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProfessionalProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Requisito 8: home com profissionais próximos + submenu de categorias
    public function index(Request $request)
    {
        $address = $request->user()->address;

        $professionals = collect();
        if ($address?->latitude) {
            $professionals = ProfessionalProfile::nearby($address->latitude, $address->longitude)
                ->with(['user.address', 'categories'])
                ->limit(12)->get();
        }

        return view('client.home', [
            'categories'    => Category::where('active', true)->orderBy('name')->get(),
            'professionals' => $professionals,
            'currentSlug'   => null,
        ]);
    }

    // View filtrada por categoria (clique no submenu)
    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->where('active', true)->firstOrFail();
        $address  = $request->user()->address;

        $query = ProfessionalProfile::query();

        if ($address?->latitude) {
            $query = ProfessionalProfile::nearby($address->latitude, $address->longitude, 50);
        } else {
            $query->approved();
        }

        $professionals = $query
            ->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id))
            ->with(['user.address', 'categories'])
            ->limit(30)->get();

        return view('client.home', [
            'categories'    => Category::where('active', true)->orderBy('name')->get(),
            'professionals' => $professionals,
            'currentSlug'   => $slug,
            'currentCategory' => $category,
        ]);
    }

    // JSON: chamado pelo JS com a posição real do navegador (Geolocation API)
    public function nearbyJson(Request $request)
    {
        $data = $request->validate([
            'lat'  => ['required', 'numeric', 'between:-90,90'],
            'lng'  => ['required', 'numeric', 'between:-180,180'],
            'slug' => ['nullable', 'string'],
        ]);

        $query = ProfessionalProfile::nearby($data['lat'], $data['lng'])
            ->with(['user.address', 'categories']);

        if (!empty($data['slug'])) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $data['slug']));
        }

        return response()->json(
            $query->limit(12)->get()->map(fn ($p) => [
                'id'         => $p->id,
                'name'       => $p->user->name,
                'photo'      => $p->photo ? asset('storage/'.$p->photo) : null,
                'city'       => $p->user->address?->city,
                'distance'   => round($p->distance_km ?? 0, 1),
                'categories' => $p->categories->pluck('name'),
                'min_price'  => $p->categories->min('pivot.price'),
                'url'        => route('profissional.show', $p),
            ])
        );
    }
}
