@extends('layouts.master', ['title' => 'Cast'])

@section('content')
    <x-container>
        <div class="col-12 col-md-8">
            <x-card-action title="Casts" class="card-body p-0" :url="route('admin.cast.index')" value="{{ $search }}" name="search">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($casts as $i => $cast)
                            <tr>
                                <td>{{ $i + $casts->firstItem() }}</td>
                                <td>
                                    <span class="avatar rounded avatar-md"
                                        style="background-image: url({{ $cast->photo }})"></span>
                                </td>
                                <td>{{ $cast->name }}</td>
                                <td>
                                    <x-button-modal :id="$cast->id" icon="edit" title=""
                                        class="btn btn-warning btn-sm" />
                                    <x-modal :id="$cast->id" title="Edit">
                                        <form action="{{ route('admin.cast.update', $cast->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="Photo" name="photo" type="file" placeholder=""
                                                :value="$cast->photo" />
                                            <x-input title="Name" name="name" type="text"
                                                placeholder="Input cast name" :value="$cast->name" />
                                            <button type="submit" class="btn btn-dark">Update</button>
                                        </form>
                                    </x-modal>
                                    <x-button-delete :id="$cast->id" title="" :url="route('admin.cast.destroy', $cast->id)"
                                        class="btn btn-danger btn-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card-action>
        </div>
        <div class="col-12 col-md-4">
            <x-card title="Generate Cast" class="card-body">
                <form action="{{ route('admin.cast.store') }}" method="POST">
                    @csrf
                    <x-input title="TMDB ID" name="tmdb_id" type="text" placeholder="Input tmdb id" value="" />
                    <button class="btn btn-dark">Generate</button>
                </form>
            </x-card>
        </div>
    </x-container>
@endsection
