
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zece Info - Pregătire Informatică</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    body {
      background: #000;
      color: #fff;
      scroll-behavior: smooth;
    }

    /* --- NAVBAR START: REWRITTEN & CORRECTED --- */
    .navbar-custom {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 40px);
      max-width: 1200px; /* Optional: constrain max width on large screens */
      background: #212121; 
      border-radius: 32px; /* Increased border radius */
      padding: 0.5rem 1.5rem;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: space-between; /* Key for alignment */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25), 0 6px 20px rgba(0, 0, 0, 0.20);
    }

    /* New style to remove left padding from logo */
    .navbar-custom .logo {
      margin-left: -1.5rem;
    }

    .navbar-custom .logo img {
      height: 90px; /* Increased height */
      width: auto;
      object-fit: contain;
      margin-top: -10px; /* Make it overlap vertically */
      margin-bottom: -10px;
    }
    
    /* Container for the navigation links */
    .navbar-links {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .navbar-custom .nav-link {
      background: transparent;
      color: #fff !important; 
      border-radius: 16px; 
      display: flex;
      align-items: center;
      justify-content: center;
      height: 70px;
      padding: 0 24px;
      font-weight: 500;
      font-size: 1.1rem;
      transition: background-color 0.3s ease;
      text-decoration: none;
    }

    .navbar-custom .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    /* Hamburger menu button */
    .navbar-toggler {
      display: none; /* Hidden on desktop */
      background: none;
      border: none;
      color: white;
      font-size: 1.8rem;
    }
    /* --- NAVBAR END --- */


    /* Sections */
    section {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      /* THIS IS THE FIX: Align content to the top */
      justify-content: flex-start; 
      padding: 120px 2rem 2rem 2rem;
      text-align: center;
    }
    
    .section-title {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 2rem;
    }

    /* Section 1: Start */
    #section1 {
      background: #000;
      justify-content: center; /* Vertically center the content */
    }

    .home-container {
      display: flex;
      flex-direction: row;
      gap: 2rem;
      align-items: stretch;
      width: 100%;
      max-width: 1200px;
    }

    #section1 .panel {
      background: #fff;
      color: #000;
      border-radius: 20px;
      flex: 1;
      padding: 3rem;
      display: flex;
      flex-direction: column; 
      align-items: center;
      justify-content: center;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1), 0 3px 10px rgba(0,0,0,0.08); 
      transition: box-shadow 0.3s ease; 
    }

    #section1 .panel:hover {
      box-shadow: 0 4px 8px rgba(0,0,0,0.15), 0 6px 20px rgba(0,0,0,0.12); 
    }
    
    /* New style for dark panel */
    #section1 .panel-dark {
      background: #212121;
      color: #fff;
    }

    .panel-dark .lead-text {
      color: #bdbdbd;
    }
    .panel-dark .divider {
      background-color: rgba(255, 255, 255, 0.2);
    }
    /* Invert primary button for dark background */
    .panel-dark .btn-custom {
      background-color: #fff;
      color: #000;
    }
    .panel-dark .btn-custom:hover {
      background-color: #ddd;
      color: #000;
    }
    /* Invert secondary button for dark background */
    .panel-dark .btn-secondary-custom {
      color: #fff;
      border-color: #fff;
    }
    .panel-dark .btn-secondary-custom:hover {
      background-color: #fff;
      color: #000;
    }
    /* Style input for dark background */
    .panel-dark .material-code-input {
      background-color: #333;
      color: #fff;
      border-bottom-color: #777;
    }
    .panel-dark .material-code-input:focus {
      border-color: #fff;
    }
    
    #section1 .panel-dark .mentor-profile h4 {
      color: #fff;
    }

    #section1 .panel-dark .mentor-profile p {
      color: #bdbdbd;
    }

    .panel h2 {
        font-weight: bold;
        margin-bottom: 1rem;
    }
    
    /* New style for subtitles in the panel */
    .panel .lead-text {
      color: #6c757d;
      margin-bottom: 1.5rem;
      max-width: 350px;
      width: 100%;
    }
    
    /* New style for the divider */
    .panel .divider {
      height: 1px;
      background-color: #e0e0e0;
      width: 80%;
      margin: 2rem 0;
    }

    .btn-custom {
        background-color: #000;
        color: #fff;
        border-radius: 50px;
        margin-top: 10px;
        padding: 1rem 2.5rem;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 3px rgba(0,0,0,0.15); 
    }
    
    .btn-custom:hover {
        background-color: #333;
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0,0,0,0.2); 
    }

    .btn-custom:active {
        transform: scale(0.98); 
        box-shadow: 0 1px 2px rgba(0,0,0,0.2); 
    }
    
    /* New style for the secondary button */
    .btn-secondary-custom {
        background-color: transparent;
        color: #000;
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: 2px solid #000;
        text-decoration: none;
    }

    .btn-secondary-custom:hover {
        background-color: #000;
        color: #fff;
        transform: scale(1.05);
    }

    .material-code-input-container {
        position: relative;
        margin-bottom: 0;
        width: 100%;
        max-width: 350px;
    }

    .material-code-input {
        width: 100%;
        padding: 0.8rem;
        font-size: 2.5rem;
        text-align: center;
        letter-spacing: 0.5em; 
        font-family: 'Lucida Console', 'Courier New', monospace;
        border: none; 
        border-bottom: 2px solid #ccc; 
        border-radius: 8px 8px 0 0; 
        background-color: #f0f0f0;
        color: #212121;
        transition: border-color 0.3s ease;
        outline: none; 
        text-transform: uppercase; /* Automatically make input uppercase */
    }
    .material-code-input:focus {
        border-color: #212121; 
    }
    
    /* --- MENTOR STYLES START: REWRITTEN --- */
    .mentors-container {
        display: flex;
        justify-content: space-evenly;
        align-items: flex-start; /* Align to top */
        width: 100%;
        gap: 2rem;
    }

    .mentor-profile {
        text-align: center;
        flex: 1; 
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    /* New wrapper for image and background */
    .mentor-image-wrapper {
      position: relative;
      width: 200px;
      height: 200px;
      margin-bottom: 1.5rem;
    }

    /* New colored shape behind the photo */
    .mentor-background-shape {
      position: absolute;
      width: 85%;
      height: 85%;
      border-radius: 16px;
      z-index: 1;
      top: 0;
      left: 0;
    }
    
    .mentor-background-shape.bg-1 { background-color: #ffc107; } /* Yellow/Orange */
    .mentor-background-shape.bg-2 { background-color: #20c997; } /* Teal/Mint */

    .mentor-photo {
        position: absolute;
        width: 90%; 
        height: 90%;
        object-fit: cover; /* Changed to cover to fill space */
        z-index: 2;
        bottom: 0;
        right: 0;
        border-radius: 16px; /* Added border radius to the photo itself */
    }

    .mentor-profile h4 {
        margin-bottom: 0.25rem;
        font-weight: bold;
    }

    .mentor-profile p {
        /* This color is now set by the panel-dark class */
        font-size: 0.9rem;
    }
    /* --- MENTOR STYLES END --- */

    /* NEW: Mentor Stats */
    .mentor-stats {
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        width: 100%;
        margin-top: 2rem; /* Replaced 'auto' with a fixed value */
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-item {
        text-align: center;
        padding: 0 0.5rem;
    }

    .stat-item h3 {
        font-size: 2.2rem;
        font-weight: bold;
        color: #fff;
        margin-bottom: 0.25rem;
    }

    .stat-item p {
        font-size: 0.9rem;
        color: #bdbdbd;
        line-height: 1.2;
        margin: 0;
    }


    .card-custom {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 15px;
        padding: 2rem;
        color: #fff;
        transition: all 0.3s ease;
        height: 100%;
        display: flex; 
        flex-direction: column; 
    }

    .card-custom:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.2);
    }
    
    .card-custom .icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #fff;
    }
    
    .card-custom h3 {
        font-weight: bold;
    }

    .btn-service {
        background-color: #fff;
        color: #000;
        border-radius: 50px;
        padding: 0.6rem 1.8rem;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        align-self: center; 
    }

    .btn-service:hover {
        background-color: #ddd;
        color: #000;
        transform: scale(1.05);
    }

    #section5 { background: #120E16; } /* Dark Purple */
    #section2 { background: #0E1116; } /* Dark Blue */
    #section3 { background: #0E1611; } /* Dark Green */
    #section4 { background: #160E0E; } /* Dark Red */

    /* --- RESPONSIVE DESIGN START --- */
    @media (max-width: 992px) {
      .navbar-links {
        display: none; /* Hide links by default */
        flex-direction: column;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: #212121;
        border-radius: 16px;
        padding: 1rem 0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.25);
      }
      .navbar-links.active {
        display: flex; /* Show dropdown when active */
      }
      .navbar-custom .nav-link {
        height: 50px;
        width: 90%;
      }
      .navbar-toggler {
        display: block; /* Show hamburger button */
      }
      .mentor-image-wrapper {
        width: 170px; 
        height: 170px;
      }
    }

    @media (max-width: 768px) {
      .home-container { flex-direction: column; }
      .mentors-container { flex-direction: column; }
      .section-title { font-size: 2.5rem; }
      .mentor-image-wrapper {
        width: 150px; 
        height: 150px;
      }
      .stat-item h3 {
        font-size: 1.8rem;
      }
      .stat-item p {
        font-size: 0.8rem;
      }
    }
      /* --- RESPONSIVE DESIGN END --- */
  </style>
</head>
<body>

  <nav class="navbar-custom">
    <div class="logo">
      <img src="https://zece.info/zeceinfoblock.png" alt="Logo">
    </div>
    <!-- Links are now wrapped in their own container -->
    <div class="navbar-links" id="navbarLinks">
        <a class="nav-link" href="#section1">Cod</a>
        <a class="nav-link" href="#section5">Servicii</a>
        <a class="nav-link" href="#section2">Contacte</a>
        <a class="nav-link" href="#section4">Informații</a>
    </div>
    <!-- Hamburger button -->
    <button class="navbar-toggler" id="navbarToggler">
        <i class="fas fa-bars"></i>
    </button>
  </nav>

  <section id="section1">
    <div class="home-container">
      <div class="panel panel-dark">
  <h2>Ai deja un cod?</h2>
  <p class="lead-text">Introdu codul pentru a accesa materialele.</p>
  
  <form action="code_router.php" method="get" class="material-code-input-container">
    <input type="text" name="code" class="material-code-input" minlength="1" maxlength="8" required>

    <button type="submit" class="btn btn-custom">Continuare</button>
  </form>

  <div class="divider"></div>
  <h2>Nu ai un cod?</h2>
  <p class="lead-text">Obține acces la resursele noastre.</p>
  <a href="#section2" class="btn btn-secondary-custom">Contactează-ne</a>
</div>

      <div class="panel panel-dark">
          <h2>Mentorii Noștri</h2>
          <div class="mentors-container">
              <div class="mentor-profile">
                  <div class="mentor-image-wrapper">
                      <div class="mentor-background-shape bg-1"></div>
                      <img src="https://zece.info/mentorAaa.png" alt="Mentor 1" class="mentor-photo">
                  </div>
                  <h4>Andrian</h4>
                  <p>Databases and Data Analytics</p>
              </div>
              <div class="mentor-profile">
                  <div class="mentor-image-wrapper">
                      <div class="mentor-background-shape bg-2"></div>
                      <img src="https://zece.info/mentorVaa.png" alt="Mentor 2" class="mentor-photo">
                  </div>
                  <h4>Vladislav</h4>
                  <p>Python, HTML and Networking</p>
              </div>
          </div>
          <div class="mentor-stats">
              <div class="stat-item">
                  <h3>400+</h3>
                  <p>Copii examinați</p>
              </div>
              <div class="stat-item">
                  <h3>2</h3>
                  <p>Ani experiență</p>
              </div>
              <div class="stat-item">
                  <h3>99.9%</h3>
                  <p>Rată de succes</p>
              </div>
          </div>
      </div>
    </div>
  </section>

  <?php
// This section can be used for server-side logic if needed
exit();
?>

  <section id="section5">
    <h1 class="section-title">Servicii</h1>
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-school"></i></div>
                    <h3>Curs Certiport Prezență Fizică</h3>
                    <p>Participă la cursurile noastre în persoană pentru o experiență de învățare directă și interactivă.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>Curs Certiport Online</h3>
                    <p>Învață în ritmul tău, de oriunde, cu acces la aceleași resurse de calitate și suport constant.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-headset"></i></div>
                    <h3>Consultanță după eșec</h3>
                    <p>Analizăm împreună ce nu a funcționat și creăm un nou plan de atac pentru succesul garantat.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-user-check"></i></div>
                    <h3>Ghidare Personală</h3>
                    <p>Beneficiază de un plan de studiu personalizat și sesiuni 1-la-1 cu mentorii noștri.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-file-alt"></i></div>
                    <h3>Teste de autoevaluare pentru examen</h3>
                    <p>Verifică-ți cunoștințele cu teste complete care simulează structura examenului oficial.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-dumbbell"></i></div>
                    <h3>Teste de antrenament</h3>
                    <p>Exersează pe capitole specifice cu seturi de probleme dedicate pentru a-ți consolida materia.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-custom">
                    <div class="icon"><i class="fas fa-video"></i></div>
                    <h3>Pachet de studiu individual cu materiale video</h3>
                    <p>Învață în ritmul tău cu lecții video explicative și materiale de suport pentru fiecare subiect.</p>
                    <a href="#section2" class="btn-service mt-auto">Detalii</a>
                </div>
            </div>
        </div>
    </div>
  </section>

<section id="section2">
  <h1 class="section-title text-center">Contact</h1>
  <div class="container">
    <div class="row justify-content-center g-4">
      
      <!-- Instagram Highlight Card -->
      <div class="col-md-6">
        <div class="card-custom text-center" style="border: 2px solid #E1306C;">
          <div class="icon" style="color: #E1306C;"><i class="fab fa-instagram fa-3x"></i></div>
          <h3>Scrie-ne pe Instagram</h3>
          <p>Cel mai rapid mod de a lua legătura cu noi este printr-un mesaj direct pe Instagram. Răspundem zilnic la toate întrebările.</p>
          <a href="https://instagram.com/zece.info" target="_blank" class="btn btn-primary" style="background-color: #E1306C; border-color: #E1306C;">
            <i class="fab fa-instagram"></i> Deschide Instagram
          </a>
        </div>
      </div>

      <!-- Email -->
      <div class="col-md-3">
        <div class="card-custom text-center">
          <div class="icon"><i class="fas fa-envelope"></i></div>
          <h3>Email</h3>
          <p>Dacă preferi, ne poți trimite un email.</p>
          <a href="mailto:zece.info@gmail.com" class="btn btn-outline-primary">zece.info@gmail.com</a>
        </div>
      </div>

      <!-- Phone -->
      <div class="col-md-3">
        <div class="card-custom text-center">
          <div class="icon"><i class="fas fa-phone"></i></div>
          <h3>Telefon</h3>
          <p>Suntem disponibili pentru apeluri între 10:00 și 18:00.</p>
          <a href="tel:+37379896797" class="btn btn-outline-primary">+373 79 896 797</a>
        </div>
      </div>
    </div>

  </div>
</section>


  <section id="section4">
    <h1 class="section-title">Despre Proiect</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-custom">
                    <p class="lead">"Zece Info" este o platformă creată pentru a ajuta elevii să se pregătească eficient pentru proba de informatică a examenului de Bacalaureat. Misiunea noastră este să oferim resurse de calitate, exerciții relevante și o metodă de învățare interactivă pentru a asigura succesul fiecărui utilizator. 🚀</p>
                </div>
            </div>
        </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const navbarToggler = document.getElementById('navbarToggler');
    const navbarLinks = document.getElementById('navbarLinks');
    const navLinks = navbarLinks.querySelectorAll('.nav-link');

    // Toggle menu on hamburger click
    navbarToggler.addEventListener('click', () => {
      navbarLinks.classList.toggle('active');
    });

    // Close menu when a link is clicked
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        if (navbarLinks.classList.contains('active')) {
          navbarLinks.classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>

