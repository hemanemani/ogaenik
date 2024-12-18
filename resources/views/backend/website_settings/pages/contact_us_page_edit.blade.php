@extends('backend.layouts.app')

@section('content')
@if (auth()->user()->can('smtp_settings') &&
        env('MAIL_USERNAME') == null &&
        env('MAIL_PASSWORD') == null)
    <div class="">
        <div class="alert alert-info d-flex align-items-center">
            {{ translate('Please Configure SMTP Setting to work all email sending functionality') }},
            <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Contact Us Page Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<ul class="nav nav-tabs nav-fill language-bar">
		@foreach (get_all_active_language() as $key => $language)
			<li class="nav-item">
				<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->slug, 'lang'=> $language->code] ) }}">
					<img src="{{ asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
					<span>{{$language->name}}</span>
				</a>
			</li>
		@endforeach
	</ul>

	<form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="lang" value="{{ $lang }}">

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Title')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $page->getTranslation('title',$lang) }}" required>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="slug">{{translate('Link')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <div class="input-group d-block d-md-flex">
                        @if($page->type == 'custom_page')
                            <div class="input-group-prepend"><span class="input-group-text flex-grow-1">{{ route('home') }}/</span></div>
                            <input type="text" class="form-control w-100 w-md-auto" placeholder="{{ translate('Slug') }}" name="slug" value="{{ $page->slug }}">
                        @else
                            <input class="form-control w-100 w-md-auto" value="{{ route('home') }}/{{ $page->slug }}" disabled>
                        @endif
                    </div>
                    <small class="form-text text-muted">{{ translate('Use character, number, hypen only') }}</small>
                </div>
            </div>
            @php
                $content = json_decode($page->getTranslation('content',$lang));
            @endphp
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="description">{{translate('Short description')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="form-control"
						placeholder="{{translate('Type here...')}}"
						name="description"
                        rows="3"
						required
					>{{ $content->description }}</textarea>
				</div>
			</div>
            <div class="form-group row">
				<label class="col-sm-2 col-from-label" for="address">{{translate('Address')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="form-control"
						placeholder="{{translate('Type here...')}}"
						name="address"
                        rows="2"
						required
					>{{ $content->address }}</textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="phone">{{translate('Phone')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Phone')}}" name="phone" value="{{ $content->phone }}" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="email">{{translate('Email')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ $content->email }}" required>
				</div>
			</div>
		</div>

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
		</div>
		<div class="card-body px-0">

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="meta_title">{{translate('Meta Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $page->meta_title }}">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="meta_description">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{!! $page->meta_description !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="keywords">{{translate('Keywords')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{!! $page->keywords !!}</textarea>
					<small class="text-muted">{{ translate('Separate with coma') }}</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="meta_image">{{translate('Meta Image')}}</label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
						</div>
						<div class="form-control file-amount">{{ translate('Choose File') }}</div>
						<input type="hidden" name="meta_image" class="selected-files" value="{{ $page->meta_image }}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update Page') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection