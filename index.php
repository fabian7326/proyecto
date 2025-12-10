<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

// trae cursos de la BD (ajusta LIMIT si quieres más/menos)
$cursosPopulares = db()->query("
  SELECT id, code, name, credits, price_cents, image_path
  FROM courses
  ORDER BY id ASC
  LIMIT 9
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Educación KESENFA</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
      /* WhatsApp flotante (si no lo tienes en css) */
      .whatsapp-float{
        position: fixed; bottom: 20px; right: 20px; z-index: 999;
        width: 60px; height: 60px; border-radius: 50%;
        display:flex; align-items:center; justify-content:center;
        background:#25d366; box-shadow:0 8px 20px rgba(0,0,0,.2);
      }
      .whatsapp-float img{ width: 35px; height:35px; }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary">
              <i class="fa fa-book me-2"></i>Educación KESENFA
            </h2>
        </a>

        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Inicio</a>
                <a href="#about" class="nav-item nav-link smoothScroll">Nosotros</a>
                <a href="#course" class="nav-item nav-link">Cursos</a>
            </div>

            <!-- botón de matrícula -->
            <a href="login.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">
              MATRICULATE AQUI<i class="fa fa-arrow-right ms-3"></i>
            </a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- WhatsApp -->
    <a href="https://wa.me/+51966799689?text=Hola%20Educación%20KESENFA%20quiero%20información" class="whatsapp-float" target="_blank">
      <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
    </a>

    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="img/carousel-1.jpg" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-sm-10 col-lg-8">
                                <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Los mejores cursos en línea</h5>
                                <h1 class="display-3 text-white animated slideInDown">La mejor plataforma de aprendizaje en línea</h1>
                                <p class="fs-5 text-white mb-4 pb-2">Edúcate online desde casa</p>
                                <a href="login.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Únete ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- About Start -->
    <div class="container-xxl py-5" id="about">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 300px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100" src="img/about.jpg" alt="" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">Acerca de Nosotros</h6>
                    <h1 class="mb-4">Bienvenido a Educación KESENFA</h1>
                    <p class="mb-4">La Educación Kesenfa es parte de un proyecto comprometido con la educación de calidad y visión de país.</p>
                    <p class="mb-4">Nuestra educación logra instalarse en el escenario nacional en la formación de cuadros técnicos.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Courses Start (DINÁMICO) -->
    <div class="container-xxl py-5" id="course">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">CURSOS VIRTUALES</h6>
                <h1 class="mb-5">Nuestros Cursos</h1>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach($cursosPopulares as $c): 
                    $img = !empty($c['image_path']) ? $c['image_path'] : "img/course-default.jpg";
                    $precio = cents_to_money((int)$c['price_cents']);
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="course-item bg-light">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid" src="<?= htmlspecialchars($img) ?>" alt="">
                        </div>

                        <div class="text-center p-4 pb-0">
                            <h3 class="mb-0">S/<?= $precio ?></h3>
                            <div class="mb-3">
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small>(<?= (int)$c['credits'] ?> créditos)</small>
                            </div>
                            <h5 class="mb-4"><?= htmlspecialchars($c['name']) ?></h5>

                            <a href="login.php" class="btn btn-primary py-md-3 px-md-3">
                              Regístrate para Matricularte
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <!-- Courses End -->

    <!-- Team Start (igual que tu plantilla) -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">INSTRUCTORES</h6>
                <h1 class="mb-5">Instructores expertos</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow fadeInUp">
                    <div class="team-item bg-light">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="img/team-1.jpg" alt="">
                        </div>
                        <div class="text-center p-4">
                            <h5 class="mb-0">Bernardo Huamán</h5>
                            <small>Instructor de Desarrollo</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp">
                    <div class="team-item bg-light">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="img/team-2.jpg" alt="">
                        </div>
                        <div class="text-center p-4">
                            <h5 class="mb-0">Elena Pizarro</h5>
                            <small>Instructora de Programación</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp">
                    <div class="team-item bg-light">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="img/team-3.jpg" alt="">
                        </div>
                        <div class="text-center p-4">
                            <h5 class="mb-0">Oscar Figueroa</h5>
                            <small>Instructor de Ing. de Sistemas</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp">
                    <div class="team-item bg-light">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="img/team-4.jpg" alt="">
                        </div>
                        <div class="text-center p-4">
                            <h5 class="mb-0">Pierina Mendoza</h5>
                            <small>Instructora de Lenguaje</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn">
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <center>Educación Kesenfa - © Copyright 2025</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
