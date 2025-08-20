@for ($i=0; $i<= count($alaUne) - 1; $i++ )
    @if ($i > 8)
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <!-- Newsletter START -->
            <div class="card mb-4">
                <div class="row g-3">
                    <div class="col-4">
                        <img class="rounded-3" src="{{$alaUne[$i]['image_cover_url']}}" alt="{{$alaUne[$i]['title']}}" style="background-size: cover; background-position: center center; height: 100px; object-fit:cover">
                    </div>
                    <div class="col-8">
                        <a href="/{{$alaUne[$i]['category_slug']}}" class="badge text-bg-danger mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>{{$alaUne[$i]['category_name']}}</a>
                        <h6><a href="/{{$alaUne[$i]['slug']}}" class="btn-link text-reset stretched-link fw-bold">{!! \Illuminate\Support\Str::words($alaUne[$i]['title'], 10, ' ...') !!}</a></h6>
                        <!-- Card info -->
                        <ul class="nav nav-divider align-items-center  d-sm-inline-block text-uppercase" style="font-size: 12px">
                            <li class="nav-item">
                                <div class="nav-link">
                                    <div class="d-flex align-items-center position-relative">

                                        <span class="ms-3"><a href="/auteurs/{{ $alaUne[$i]['author_slug'] }}" class="stretched-link text-reset btn-link">{{ $alaUne[$i]['author_name'] }}</a></span>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">{{ date('d/m/Y', strtotime($alaUne[$i]['date_publish'])) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Newsletter END -->
    @endif
@endfor


