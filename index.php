<?php
// index.php - sasa ni faili ya PHP badala ya HTML
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>LMS - Laundry Management System</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>


        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-secondary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Topbar Start -->
        <div class="container-fluid bg-primary px-5 d-none d-lg-block">
            <div class="row gx-0 align-items-center">
                <div class="col-lg-5 text-center text-lg-start mb-lg-0">
                    <div class="d-flex">
                        <a href="#" class="text-muted me-4"><i class="fas fa-envelope text-secondary me-2"></i>laundry@example.com</a>
                        <a href="#" class="text-muted me-0"><i class="fas fa-phone-alt text-secondary me-2"></i>+255 123 456 789</a>
                    </div>
                </div>
                <div class="col-lg-3 row-cols-1 text-center mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center" style="height: 45px;">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid nav-bar p-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="display-5 text-secondary m-0"><img src="img/logo.jpg" class="img-fluid" alt="">LMS</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.php" class="nav-item nav-link">About Us</a>
                        <a href="service.php" class="nav-item nav-link">Our Services</a>
                        <a href="contact.php" class="nav-item nav-link">Contact Us</a>
                    </div>
                    <a href="login.php" class="btn btn-primary border-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0">Login</a>
                </div>
            </nav>
        </div>
        <!-- Navbar & Hero End -->

        <!-- Carousel Start -->
        <div class="carousel-header">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="img/picture2.jpg" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="text-center p-4" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-3 mb-md-4 wow fadeInUp" data-wow-delay="0.1s">Professional Laundry Services</h4>
                                <h1 class="display-1 text-capitalize text-white mb-3 mb-md-4 wow fadeInUp" data-wow-delay="0.3s">Clean Clothes, Happy Life!</h1>
                                <p class="text-white mb-4 mb-md-5 fs-5 wow fadeInUp" data-wow-delay="0.5s">We provide fast, affordable, and quality laundry and dry cleaning services. Order online and relax!</p>
                                <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp" data-wow-delay="0.7s" href="#">Order Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/picture1.jpg" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="text-center p-4" style="max-width: 900px;">
                                <h5 class="text-white text-uppercase fw-bold mb-3 mb-md-4 wow fadeInUp" data-wow-delay="0.1s">Your Trusted Laundry Partner</h5>
                                <h1 class="display-1 text-capitalize text-white mb-3 mb-md-4 wow fadeInUp" data-wow-delay="0.3s">Doorstep Pickup & Delivery</h1>
                                <p class="text-white mb-4 mb-md-5 fs-5 wow fadeInUp" data-wow-delay="0.5s">We pick up your laundry, wash it with care, and deliver it back fresh and clean within 24 hours.</p>
                                <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp" data-wow-delay="0.7s" href="#">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-secondary wow fadeInLeft" data-wow-delay="0.2s" aria-hidden="false"></span>
                    <span class="visually-hidden-focusable">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-secondary wow fadeInRight" data-wow-delay="0.2s" aria-hidden="false"></span>
                    <span class="visually-hidden-focusable">Next</span>
                </button>
            </div>
        </div>

        <!-- Carousel End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h4 class="modal-title text-secondary mb-0" id="exampleModalLabel">Search by keyword</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->



        <!-- About Start -->
       <div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-xl-5 wow fadeInLeft" data-wow-delay="0.1s">
                <div class="bg-light rounded">
                    <img src="img/about-2.png" class="img-fluid w-100" style="margin-bottom: -7px;" alt="Image">
                    <img src="img/about-3.jpg" class="img-fluid w-100 border-bottom border-5 border-primary" style="border-top-right-radius: 300px; border-top-left-radius: 300px;" alt="Image">
                </div>
            </div>
            <div class="col-xl-7 wow fadeInRight" data-wow-delay="0.3s">
                <h5 class="sub-title pe-3">About the System</h5>
                <h1 class="display-5 mb-4">We’re a Reliable Laundry Management Solution.</h1>
                <p class="mb-4">Our Laundry Management System simplifies operations, tracks orders in real-time, and ensures customer satisfaction. Designed for laundromats, dry cleaners, and delivery services, this system makes managing garments, staff, and customers more efficient than ever.</p>
                <div class="row gy-4 align-items-center">
                    <div class="col-12 col-sm-6 d-flex align-items-center">
                        <i class="fas fa-map-marked-alt fa-3x text-secondary"></i>
                        <h5 class="ms-4">Smart Pickup & Delivery Tracking</h5>
                    </div>
                    <div class="col-12 col-sm-6 d-flex align-items-center">
                        <i class="fas fa-passport fa-3x text-secondary"></i>
                        <h5 class="ms-4">Easy Order Management</h5>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="bg-light text-center rounded p-3">
                            <div class="mb-2">
                                <i class="fas fa-ticket-alt fa-4x text-primary"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-2">10+</h1>
                            <p class="text-muted mb-0">Years of Experience</p>
                        </div>
                    </div>
                    <div class="col-8 col-md-9">
                        <div class="mb-5">
                            <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i> 100% Reliable & Secure System</p>
                            <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i> Fast and Automated Workflows</p>
                            <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i> Real-time Notifications & Updates</p>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div id="phone-tada" class="d-flex align-items-center justify-content-center me-4">
                                <a href="" class="position-relative wow tada" data-wow-delay=".9s">
                                    <i class="fa fa-phone-alt text-primary fa-3x"></i>
                                    <div class="position-absolute" style="top: 0; left: 25px;">
                                        <span><i class="fa fa-comment-dots text-secondary"></i></span>
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <span class="text-primary">Need help or a demo?</span>
                                <span class="text-secondary fw-bold fs-5" style="letter-spacing: 2px;">Call Us: +0123 456 7890</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

       
        <!-- Counter Facts End -->


        <!-- Services Start -->
       <div class="container-fluid service overflow-hidden pt-5">
    <div class="container py-5">
        <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="sub-style">
                <h5 class="sub-title text-primary px-3">Laundry Services</h5>
            </div>
            <h1 class="display-5 mb-4">Smart Laundry, Clean Results</h1>
            <p class="mb-0">Discover our powerful Laundry Management System designed to streamline washing, tracking, and delivering garments with ease. Whether you run a hotel laundry, hospital linen service, or a personal pickup-drop laundry business — we’ve got you covered!</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/picture@.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Dry Cleaning</a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="login.php">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Dry Cleaning</h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Professional dry cleaning services with real-time tracking and garment care alerts integrated into the system.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill py-3 px-5" href="login.php">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/nguo.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Express Laundry</a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="#">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Express Laundry</h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Fast and efficient wash and fold services, designed for quick turnaround using advanced scheduling.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5" href="login.php">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/Ironing.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Linen Services</a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="#">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Linen Services</h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Tailored for hospitals and hotels — manage large volumes of linen with automated sorting and delivery tracking.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5" href="login.php">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/service4.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Pickup & Delivery</a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="#">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Pickup & Delivery</h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Efficient route planning and delivery updates for customers. Integrated with SMS/email alerts.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5" href="login.php">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/laundry2.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Subscription Plans</a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="#">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Subscription Plans</h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Offer weekly or monthly laundry packages. Manage customer preferences and automate billing cycles.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5" href="login.php">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <div class="service-inner">
                        <div class="service-img">
                            <img src="img/ironing.jpg" class="img-fluid w-100 rounded" alt="Image">
                        </div>
                        <div class="service-title">
                            <div class="service-title-name">
                                <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                    <a href="#" class="h4 text-white mb-0">Uniform </a>
                                </div>
                                <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4" href="#">Register Now</a>
                            </div>
                            <div class="service-content pb-4">
                                <a href="#"><h4 class="text-white mb-4 py-3">Uniform </h4></a>
                                <div class="px-4">
                                    <p class="mb-4">Track and manage uniforms for schools, companies or staff. Barcode scanning included for accountability.</p>
                                    <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5" href="">Register Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Services End -->
<div class="container-fluid service overflow-hidden pt-5">
    <div class="container py-5">
        <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="sub-style">
                <h5 class="sub-title text-primary px-3">Laundry Services</h5>
            </div>
            <h1 class="display-5 mb-4">Our Professional Laundry Solutions</h1>
        </div>
        <div class="row g-4">
            <!-- Picha zote 8 -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/beseni.jpg" class="img-fluid w-100 rounded viewable-img" alt="Wash & Fold" data-index="0" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/gumzo.jpg" class="img-fluid w-100 rounded viewable-img" alt="Dry Cleaning" data-index="1" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/service5.jpg" class="img-fluid w-100 rounded viewable-img" alt="Ironing Service" data-index="2" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/service1.jpg" class="img-fluid w-100 rounded viewable-img" alt="Pickup & Delivery" data-index="3" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <!-- Repeat 4 more -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/picture@.jpg" class="img-fluid w-100 rounded viewable-img" alt="Wash & Fold" data-index="4" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/ironing.jpg" class="img-fluid w-100 rounded viewable-img" alt="Dry Cleaning" data-index="5" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/service7 (2).jpg" class="img-fluid w-100 rounded viewable-img" alt="Ironing Service" data-index="6" style="cursor:pointer;">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-item">
                    <div class="service-img position-relative">
                        <img src="img/laundry2.jpg" class="img-fluid w-100 rounded viewable-img" alt="Pickup & Delivery" data-index="7" style="cursor:pointer;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark position-relative text-center">
      <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <img src="" id="modalImage" class="img-fluid rounded" style="max-height: 80vh; margin: auto;" alt="">

      <button id="prevBtn" class="btn btn-outline-light position-absolute top-50 start-0 translate-middle-y ms-2">&#10094;</button>
      <button id="nextBtn" class="btn btn-outline-light position-absolute top-50 end-0 translate-middle-y me-2">&#10095;</button>

      <div id="modalError" class="text-danger mt-2" style="display:none;">Error loading image!</div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (hakikisha umejumuisha hivi karibuni) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const images = [
    "img/service-1.jpg",
    "img/service-2.jpg",
    "img/service-3.jpg",
    "img/service-4.jpg",
    "img/service-1.jpg",
    "img/service-2.jpg",
    "img/service-3.jpg",
    "img/service-4.jpg"
  ];

  let currentIndex = 0;
  const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
  const modalImage = document.getElementById('modalImage');
  const modalError = document.getElementById('modalError');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');

  function showImage(index) {
    modalError.style.display = 'none';
    currentIndex = index;
    modalImage.src = images[index];
    modal.show();
  }

  document.querySelectorAll('.viewable-img').forEach(img => {
    img.addEventListener('click', () => {
      const index = parseInt(img.getAttribute('data-index'));
      showImage(index);
    });
  });

  prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
    showImage(currentIndex);
  });

  nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
    showImage(currentIndex);
  });

  modalImage.addEventListener('error', () => {
    modalError.style.display = 'block';
  });
</script>


       
        <!-- Contact End -->


        <!-- Footer Start -->
<div class="container-fluid footer bg-dark text-light py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-4">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-md-6 col-lg-4">
                <h5 class="text-secondary mb-4">Contact Info</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-2"></i>123 Street, Dar es Salaam, Tanzania</p>
                <p class="mb-2"><i class="fas fa-envelope me-2"></i>info@laundrycompany.com</p>
                <p class="mb-2"><i class="fas fa-phone me-2"></i>+255 712 345 678</p>
                <div class="d-flex pt-3">
                    <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Opening Hours -->
            <div class="col-md-6 col-lg-4">
                <h5 class="text-secondary mb-4">Opening Hours</h5>
                <p class="mb-1">Monday - Friday: <span class="text-white">08:00 AM - 07:00 PM</span></p>
                <p class="mb-1">Saturday: <span class="text-white">09:00 AM - 05:00 PM</span></p>
                <p class="mb-1">Sunday: <span class="text-white">Closed</span></p>
            </div>

            <!-- About Laundry -->
            <div class="col-md-12 col-lg-4">
                <h5 class="text-secondary mb-4">About Our Laundry</h5>
                <p class="mb-0">
                    We offer professional and affordable laundry services. Whether it's Wash & Fold, Dry Cleaning, or Ironing — our team ensures your clothes are clean, fresh, and perfectly handled.
                </p>
            </div>
        </div>
    </div>
</div>


        <!-- Footer End -->

        
        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-md-0">
                        <span class="text-white"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 text-center text-md-end text-white">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom text-white" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom text-white" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>