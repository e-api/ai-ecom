@extends('frontend.layouts.app')

@section('title', $category->meta_title ?? $category->name)
@section('meta_description', $category->meta_description ?? '')
@section('meta_keywords', $category->meta_keywords ?? '')

@section('content')

@include('frontend.category.partials.filters')

@include('frontend.category.partials.products')

@endsection