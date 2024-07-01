@extends('layouts.app')

@section('title', 'Write a Story')

@section('content')

<!-- **************** MAIN CONTENT START **************** -->
<main>

  <!-- Hero event START -->
  <section class="pt-5 pb-0 position-relative" style="background-image: url(assets/images/bg/07.jpg); background-repeat: no-repeat; background-size: cover; background-position: top center;">
  <div class="bg-overlay bg-dark opacity-8"></div>
    <!-- Container START -->
    <div class="container">
      <div class="pt-5">
        <div class="row position-relative">
          <div class="col-xl-8 col-lg-10 mx-auto pt-sm-5 text-center">
            <!-- Title -->
            <h1 class="text-white">Design Your Journey</h1>
            <p class="text-white">Fill in a few details to start your Journey</p>
            <div class="mx-auto bg-mode shadow rounded p-4 mt-5">
              <!-- Form START -->
              <form class="row g-3 justify-content-center">
                <div class="col-md-5">
                  <!-- What -->
                  <div class="input-group">
                    <input class="form-control form-control-lg me-1 pe-5" type="text" placeholder="What">
                  </div>
                </div>
                <div class="col-md-5">
                  <!-- Where -->
                  <div class="input-group">
                    <input class="form-control form-control-lg me-1 pe-5" type="text" placeholder="Where">
                    <a class="position-absolute top-50 end-0 translate-middle-y text-secondary px-3 z-index-9" href="#"> <i class="fa-solid fa-crosshairs"></i> </a>
                  </div>
                </div>
                <div class="col-md-2 d-grid">
                  <!-- Search -->
                  <a class="btn btn-lg btn-primary" href="#">Search</a>
                </div>
              </form>
              <!-- Form END -->
            </div>
          </div>
          <div class="mb-n5 mt-3 mt-lg-5" style="height: 50px;">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Hero event END -->
  
  <!-- Top Destinations START -->
  <section class="bg-mode pb-5 pt-0 pt-lg-5">
    <div class="container">
      <div class="row">
        <div class="col-12 mb-3">
          <!-- Title -->
          <h4>Top Destinations </h4>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/02.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">California</a></h5>
                <!-- Card info -->
                <span class="text-white small">Business & Conferences</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/04.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">Los Angeles</a></h5>
                <!-- Card info -->
                <span class="text-white small">Events & Parties</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/05.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
                <!-- Card info -->
                <span class="text-white small">Arts & Entertainment</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/01.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
                <!-- Card info -->
                <span class="text-white small">Arts & Entertainment</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
      </div>
    </div>
  </section>
  <!-- Top Destinations END -->

  <!-- Explore Groups START -->
  <section class="pt-5 pb-5">
    <div class="container">
      <div class="row">
        <div class="col-12 mb-3">
          <!-- Title -->
          <h4>Top Destinations </h4>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/02.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">California</a></h5>
                <!-- Card info -->
                <span class="text-white small">Business & Conferences</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/04.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">Los Angeles</a></h5>
                <!-- Card info -->
                <span class="text-white small">Events & Parties</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/05.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
                <!-- Card info -->
                <span class="text-white small">Arts & Entertainment</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
        <div class="col-sm-6 col-lg-3">
          <!-- Card START -->
          <div class="card card-overlay-bottom card-img-scale">
            <!-- Card Image -->
            <img class="card-img" src="assets/images/albums/01.jpg" alt="">
            <!-- Card Image overlay -->
            <div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
              <div class="w-100 mt-auto">
                <!-- Card title -->
                <h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
                <!-- Card info -->
                <span class="text-white small">Arts & Entertainment</span>
              </div>
            </div>
          </div>
          <!-- Card END -->
        </div>
      </div>
    </div>
  </section>
  <!-- Explore Groups END -->

</main>
<!-- **************** MAIN CONTENT END **************** -->

@include('layouts.footer')

@endsection

@push('scripts')
  <!-- Inline JavaScript code -->
  <script>
    var current_page = 'index';
    $(document).ready(function () {
    });
  </script>

@endpush
