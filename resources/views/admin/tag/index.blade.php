@extends('layouts.master', ['title' => 'Tag'])

@section('content')
    <x-container>
        <div class="col-12 col-md-8">
            <x-card-action title="Tags" class="card-body p-0" :url="route('admin.tag.index')" :value="$search" name="search">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tag Name</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $i => $tag)
                            <tr>
                                <td>{{ $i + $tags->firstItem() }}</td>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <x-button-modal :id="$tag->id" icon="edit" title=""
                                        class="btn btn-warning btn-sm" />
                                    <x-modal :id="$tag->id" title="Edit">
                                        <form action="{{ route('admin.tag.update', $tag->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="Name" name="name" type="text"
                                                placeholder="Input tag name" :value="$tag->name" />
                                            <button type="submit" class="btn btn-dark">Update</button>
                                        </form>
                                    </x-modal>
                                    <x-button-delete :id="$tag->id" title="" :url="route('admin.tag.destroy', $tag->id)"
                                        class="btn btn-danger btn-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card-action>
            <div class="d-flex justify-content-end">{{ $tags->links() }}</div>
        </div>
        <div class="col-12 col-md-4">
            <x-card title="Create Tag" class="card-body">
                <form action="{{ route('admin.tag.store') }}" method="POST">
                    @csrf
                    <x-input title="Name" name="name" type="text" placeholder="Input tag name" :value="old('name')" />
                    <button type="submit" class="btn btn-dark">Create</button>
                </form>
            </x-card>
        </div>
    </x-container>
@endsection
