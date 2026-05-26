@extends('admin.layouts.app')

@section('head-tag')
<title>admin</title>
@endsection

@section('content')

<h1>hi</h1><br>

<div class="flex flex-col md:flex-row gap-5 md:gap-20 w-full md:h-50 text-2xl">
    <div class="bg-red-100 shadow-md rounded w-full md:w-1/3 p-5 flex items-center justify-center h-50 md:h-auto">
        <span class="">users</span>
    </div>
    <div class="bg-blue-100 shadow-md rounded w-full md:w-1/3 p-5 flex items-center justify-center h-50 md:h-auto">
        <span class="">users</span>
    </div>
    <div class="bg-green-100 shadow-md rounded w-full md:w-1/3 p-5 flex items-center justify-center h-50 md:h-auto">
        <span class="">users</span>
    </div>
</div>

@endsection