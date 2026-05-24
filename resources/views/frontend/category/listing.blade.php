@extends('frontend.layouts.app')

@section('title', $category->name ?? 'Product Listing')

@section('content')

@include('frontend.category.partials.filters')

@include('frontend.category.partials.products')

@endsection