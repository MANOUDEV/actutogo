<!-- SVG decoration right START -->
<section class="py-4 card-grid" style="margin-top: -20px">
    
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <!-- Slider START -->
                <div class="tiny-slider">
                    <div class="tiny-slider-inner"
                        data-autoplay="true"
                        data-hoverpause="true"
                        data-gutter="24"
                        data-arrow="false"
                        data-dots="false"
                        data-items-md="2" 
                        data-items-sm="2" 
                        data-items-xs="1"
                        data-items="3" >
                        @for ($i=0; $i <= count($alaUne) - 1; $i++ )
                            @if ($i <= 9)
                                <div class="card">
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <img class="rounded-3" src="{{$alaUne[$i]['image_cover_url']}}" alt="{{$alaUne[$i]['title']}}" style="background-size: cover; background-position: center center; height: 100px; object-fit:cover">
                                        </div>
                                        <div class="col-8">
                                            <h6><a href="/{{$alaUne[$i]['slug']}}" class="btn-link text-reset stretched-link fw-bold">{!! \Illuminate\Support\Str::words($alaUne[$i]['title'], 10, ' ...') !!}</a></h6>
                                            <!-- Card info -->
                                            <ul class="nav nav-divider align-items-center  d-sm-inline-block text-uppercase" style="font-size: 12px">
                                                <li class="nav-item">
                                                    <div class="nav-link">
                                                        <div class="d-flex align-items-center position-relative">

                                                            <span class="ms-3"><a href="/authors/{{ $alaUne[$i]['author_slug'] }}" class="stretched-link text-reset btn-link">{{ $alaUne[$i]['author_name'] }}</a></span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nav-item">{{ date('d/m/Y', strtotime($alaUne[$i]['date_publish'])) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>

        </div> 
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="col-12">
                    <div class="tiny-slider arrow-hover arrow-blur arrow-round rounded-3 overflow-hidden">
                        <div class="tiny-slider-inner"
                        data-autoplay="true"
                        data-hoverpause="true"
                        data-gutter="24"
                        data-arrow="true"
                        data-dots="false"
                        data-items-xl="1"
                        data-items-lg="1"
                        data-items-md="1"
                        data-items-sm="1"
                        data-items-xs="1">
                        <!-- Card item START -->
                            @for ($i=0; $i<= count($alaUne) - 1; $i++ )

                                @if ($i <= 16)
                                    <div>
                                        <div class="card card-overlay-bottom card-img-scale">
                                            <!-- Card Image -->
                                            <img class="card-img alaune_first_section_img_responsive" src="{{$alaUne[$i]['image_cover_url']}}" alt="{!! $alaUne[$i]['title'] !!}">
                                            <!-- Card Image overlay -->
                                            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">

                                                <div class="container px-3 my-auto">
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <div class="mb-2">
                                                                <!-- Card category -->
                                                                <a href="{{$alaUne[$i]['category_slug']}}" class="badge text-bg-primary"><i class="fas fa-circle me-2 small fw-bold"></i>{{$alaUne[$i]['category_name']}}</a>
                                                            </div>
                                                            <!-- Card title -->
                                                            <h2 class="text-white mb-2"><a href="/{{ $alaUne[$i]['slug'] }}" class="btn-link text-reset stretched-link">{!! \Illuminate\Support\Str::words($alaUne[$i]['title'], 9, ' ...') !!}</a></h4>
                                                            <div class="text-white-force mb-2">
                                                                <div>{!! $alaUne[$i]['truncate_content'] !!}</div>
                                                            </div>
                                                            <!-- Card info -->
                                                            <ul class="nav nav-divider text-white-force align-items-center d-none d-sm-inline-block">
                                                                <li class="nav-item">
                                                                    <div class="nav-link">
                                                                        <div class="d-flex align-items-center position-relative">
                                                                            <div class="avatar avatar-sm">
                                                                                <div class="avatar-img rounded-circle bg-primary">
                                                                                    <span class="text-light position-absolute top-50 start-50 translate-middle fw-bold">{{ strtoupper($alaUne[$i]['author_name'][0].''.$alaUne[$i]['author_name'][1]) }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <span class="ms-3">par <a href="/authors/{{ $alaUne[$i]['author_slug'] }}" class="stretched-link text-reset btn-link">{{ $alaUne[$i]['author_name'] }}</a></span>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="nav-item">{{ date('d/m/Y', strtotime($alaUne[$i]['date_publish'])) }}</li>
                                                                <li class="nav-item"><a href="#" class="btn-link"><i class="far fa-thumbs-up me-1"></i> {{ $alaUne[$i]['likes_count'] }}</a></li>
                                                                <li class="nav-item"><a href="#" class="btn-link"><i class="far fa-comment-alt me-1"></i> {{ $alaUne[$i]['comment_count'] }}</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>

                    </div>
                </div>
            </div>
            
        </div>

    </div>
    
</section>
