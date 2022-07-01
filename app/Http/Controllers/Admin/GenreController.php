<?php

namespace App\Http\Controllers\Admin;

use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $genres = Genre::when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->paginate(6)->withQueryString();

        return view('admin.genre.index', compact('genres', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  // check genre name is exist or not
        $data = Genre::where('name', $request->name)->first();

        // if exist, return back with error message
        if($data){
            return back()->with('toast_error', 'Genre already exists');
        }

        // get genre from tmdb api where genre name same $request->name
        $genre = Http::withToken(config('services.mdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list');

        // if genre success
        if($genre->successful()){
            $genreJson = $genre->json();
            foreach($genreJson as $dataGenre){
                foreach($dataGenre as $nameGenre){
                    $genre = Genre::where('tmbd_id', $nameGenre['id'])->first();
                    if(!$genre){
                        Genre::create([
                            'tmdb_id' => $nameGenre['id'],
                            'name' => $nameGenre['name'],
                        ]);
                    }
                }
            }
            return back()->with('toast_succes', 'Genre created successfully');
        }else{
            // if genre failed, return back with error message
            return back()->with('toast_error', 'Genre not found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
