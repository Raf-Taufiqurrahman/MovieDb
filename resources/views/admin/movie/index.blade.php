@extends('layouts.master', ['title' => 'Movie'])

@section('content')
    <x-container>
        <div class="col-12 col-md-8">
            <x-card-action title="Movies" class="card-body p-0" :url="route('admin.movie.index')" value="{{ $search }}" name="search">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Poster</th>
                            <th>Name</th>
                            <th>Genres</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movies as $i => $movie)
                            <tr>
                                <td>{{ $i + $movies->firstItem() }}</td>
                                <td>
                                    @if ($movie->created_at == $movie->updated_at)
                                        <span class="avatar rounded avatar-md"
                                            style="background-image: url({{ 'https://image.tmdb.org/t/p/w500/' . $movie->poster }})"></span>
                                    @else
                                        <span class="avatar rounded avatar-md"
                                            style="background-image: url({{ asset('storage/movies/' . $cast->poster) }})"></span>
                                    @endif
                                </td>
                                <td>{{ $movie->title }}</td>
                                <td>
                                    @foreach ($movie->genres as $genre)
                                        <li>{{ $genre->name }}</li>
                                    @endforeach
                                </td>
                                <td>{{ date('H:i', mktime(0, $movie->runtime)) }}</td>
                                <td>
                                    <x-button-modal :id="$movie->id" icon="edit" title=""
                                        class="btn btn-warning btn-sm" />
                                    <x-modal :id="$movie->id" title="Edit">
                                        <form action="{{ route('admin.movie.update', $movie->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="poster" name="poster" type="file" placeholder=""
                                                :value="$movie->poster" />
                                            <x-input title="Title" name="title" type="text"
                                                placeholder="Input movie title" :value="$movie->title" />
                                            <x-input title="Language" name="lang" type="text"
                                                placeholder="Input movie language" :value="$movie->lang" />
                                            <x-input title="Runtime" name="runtime" type="number"
                                                placeholder="Input movie duration" :value="$movie->runtime" />
                                            <x-multi-select name="genres[]" title="Genres">
                                                @foreach ($genres as $genre)
                                                    <option value="{{ $genre->id }}"
                                                        {{ $movie->genres()->find($genre->id) ? 'selected' : '' }}>
                                                        {{ $genre->name }}
                                                    </option>
                                                @endforeach
                                            </x-multi-select>
                                            <x-textarea title="Overview" name="overview" placeholder="">
                                                {{ $movie->overview }}</x-textarea>
                                            <button type="submit" class="btn btn-dark">Update</button>
                                        </form>
                                    </x-modal>
                                    <x-button-delete :id="$movie->id" title="" :url="route('admin.movie.destroy', $movie->id)"
                                        class="btn btn-danger btn-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card-action>
        </div>
        <div class="col-12 col-md-4">
            <x-card title="Generate Movie" class="card-body">
                <form action="{{ route('admin.movie.store') }}" method="POST">
                    @csrf
                    <x-input title="TMDB ID" name="tmdb_id" type="text" placeholder="Input tmdb id" value="" />
                    <button class="btn btn-dark">Generate</button>
                </form>
            </x-card>
        </div>
    </x-container>
@endsection
