 <!-- SVG decoration right START -->
<div class="container">
    <div class="row d-flex align-items-center my-2">
        <!-- Top bar left -->
        <!-- Top bar left -->
        @if(count($annonces) !== 0)
            <div class="col-sm-12 d-sm-flex align-items-center">
                <!-- Title -->
                <div class="me-3">
                    <span class="badge bg-primary p-2 px-3 text-white">Annonces:</span>
                </div>
                <!-- Slider -->
                <div class="tiny-slider arrow-end arrow-xs arrow-bordered arrow-round arrow-md-none">
                    <div class="tiny-slider-inner"
                        data-autoplay="true"
                        data-hoverpause="true"
                        data-gutter="0"
                        data-arrow="true"
                        data-dots="false"
                        data-items="1">
                        <!-- Slider items -->
                        @foreach($annonces as $result)
                            {!! $result->title !!}
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-12 d-sm-flex align-items-center">
                <!-- Title -->
                <div class="me-3">
                    <span class="badge bg-primary p-2 px-3 text-white">Tendances:</span>
                </div>
                <!-- Slider -->
                <div class="tiny-slider arrow-end arrow-xs arrow-bordered arrow-round arrow-md-none">
                    <div class="tiny-slider-inner"
                        data-autoplay="true"
                        data-hoverpause="true"
                        data-gutter="0"
                        data-arrow="true"
                        data-dots="false"
                        data-items="1">
                        <!-- Slider items -->
                        @foreach($tendances as $result)
                            <div>  <a href="#" class="text-reset btn-link"> {!! $result->title !!} </a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Divider -->
    <div class="border-bottom border-2 border-primary opacity-1"></div>

    <div class="row g-4" style="margin-top: -20px">

        <div class="col-lg-7 col-sm-12 col-xs-12">
            @include('sectionHomePage.alaUne.sections.alaUneFirstSection')
        </div>
        <div class="col-lg-5 col-sm-12 col-xs-12">
            @include('sectionHomePage.alaUne.sections.alaUneSecondSection')
        </div>

    </div>
</div>
