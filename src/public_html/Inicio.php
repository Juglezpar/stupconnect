<?php
require __DIR__ . '/../../autenticacion.php';
require_once '../Back-end/utils.php'; 


$dao = new DAO();

//Apply the filters if they exist
$filter_sector = isset($_GET['sector']) ? trim($_GET['sector']) : '';
$filter_skill  = isset($_GET['skills']) ? trim($_GET['skills']) : '';
$filter_country = isset($_GET['country']) ? trim($_GET['country']) : '';

// Prepare the WHERE clause and parameters for the query
$whereClause = "WHERE 1=1";  // Always true (If no filter, no condition is added)
$params = array();
if ($filter_sector !== '') {
    $whereClause .= " AND sector = :sector";
    $params['sector'] = $filter_sector;
}
if ($filter_skill !== '') {
    $whereClause .= " AND skills = :skills";
    $params['skills'] = $filter_skill;
}
if ($filter_country !== '') {
    $whereClause .= " AND place = :country";
    $params['country'] = $filter_country;
}

// Popular startups: The 8 most popular startups order by number of appllicaitons
$sql_popular = "SELECT * FROM startup $whereClause ORDER BY applications DESC";
$popularStartups = $dao->ExecuteQuery($sql_popular, $params);

// 2. New startups: the 8 startups with the most recent creation date
$sql_new = "SELECT * FROM startup $whereClause ORDER BY created DESC ";
$newStartups = $dao->ExecuteQuery($sql_new, $params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StartConnect</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <!-- Tu hoja de estilos personalizada -->
  <link href="Inicio.css" rel="stylesheet">
  <link href="Footer.css" rel="stylesheet">
  <link href="Header.css" rel="stylesheet">
  <link href="Filtros.css" rel="stylesheet">
  <link href="Responsive.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Goblin+One&display=swap" rel="stylesheet">
</head>
<body>
  <header class="head">
    <div class="containerHeader">
      <div class="header-left">
        <button class="AboutUs" id="AboutUsButton">
          <i class="bi bi-info-circle"></i>About Us</button>
      </div>
      <div class="header-center">
        <button class="WEBName" id="LogoButton">StUpConnect</button>
      </div>
        <div class="header-right">
          <div class="buttons">
          <button id="profileButton" class="fa fa-user-circle profile"></button>
          <button class="PostSt btn btn-light btn-lg" id="PostBtn">Post Start Up</button>
        </div>
      </div>
    </div>
  </header>
  <!-- Sector filter -->
  <div class="container mt-4">
    <form method="get" class="filter-form mb-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="filterSector" class="form-label">Sector💼</label>
          <select class="form-control" id="filterSector" name="sector">
            <option value="">All Sectors</option>
            <option value="ClimateTech" <?php if($filter_sector=="ClimateTech") echo "selected"; ?>>🌍 ClimateTech</option>
            <option value="Cybersecurity" <?php if($filter_sector=="Cybersecurity") echo "selected"; ?>>🛡️ Cybersecurity</option>
            <option value="E-commerce" <?php if($filter_sector=="E-commerce") echo "selected"; ?>>🛒 E-commerce</option>
            <option value="Education" <?php if($filter_sector=="Education") echo "selected"; ?>>🎓 Education</option>
            <option value="Finance" <?php if($filter_sector=="Finance") echo "selected"; ?>>💰 Finance</option>
            <option value="Health" <?php if($filter_sector=="Health") echo "selected"; ?>>🏥 Health</option>
            <option value="IA" <?php if($filter_sector=="IA") echo "selected"; ?>>🤖 IA</option>
            <option value="Marketing" <?php if($filter_sector=="Marketing") echo "selected"; ?>>📣 Marketing</option>
            <option value="Mobile Apps" <?php if($filter_sector=="Mobile Apps") echo "selected"; ?>>📱 Mobile Apps</option>
            <option value="Other" <?php if($filter_sector=="Other") echo "selected"; ?>>❓ Other</option>
            <option value="Retail" <?php if($filter_sector=="Retail") echo "selected"; ?>>🛍️ Retail</option>
            <option value="Technology" <?php if($filter_sector=="Technology") echo "selected"; ?>>💻 Technology</option>
            <option value="Web3" <?php if($filter_sector=="Web3") echo "selected"; ?>>🌐 Web3</option>
            <option value="Web development" <?php if($filter_sector=="Web development") echo "selected"; ?>>🛠️ Web development</option>
            <option value="Youtube" <?php if($filter_sector=="Youtube") echo "selected"; ?>>📹 Youtube</option>
          </select>
        </div>
        <!-- Skill filter -->
        <div class="col-md-4">
          <label for="filterSkills" class="form-label">Skill 🛠️</label>
          <select class="form-control" id="filterSkills" name="skills">
            <option value="">All Skills</option>
            <option value="Android" <?php if($filter_skill=="Android") echo "selected"; ?>>🤖 Android</option>
            <option value="Backend" <?php if($filter_skill=="Backend") echo "selected"; ?>>🖥️ Backend</option>
            <option value="C++" <?php if($filter_skill=="C++") echo "selected"; ?>>💻 C++</option>
            <option value="Cloud" <?php if($filter_skill=="Cloud") echo "selected"; ?>>☁️ Cloud</option>
            <option value="Crypto" <?php if($filter_skill=="Crypto") echo "selected"; ?>>💎 Crypto</option>
            <option value="Design" <?php if($filter_skill=="Design") echo "selected"; ?>>🎨 Design</option>
            <option value="DevOps" <?php if($filter_skill=="DevOps") echo "selected"; ?>>🛠️ DevOps</option>
            <option value="Engineer" <?php if($filter_skill=="Engineer") echo "selected"; ?>>🔧 Engineer</option>
            <option value="Finance" <?php if($filter_skill=="Finance") echo "selected"; ?>>💰 Finance</option>
            <option value="FrontEnd" <?php if($filter_skill=="FrontEnd") echo "selected"; ?>>🌐 FrontEnd</option>
            <option value="FullStack" <?php if($filter_skill=="FullStack") echo "selected"; ?>>💼 FullStack</option>
            <option value="Golang" <?php if($filter_skill=="Golang") echo "selected"; ?>>🐹 Golang</option>
            <option value="Java" <?php if($filter_skill=="Java") echo "selected"; ?>>☕ Java</option>
            <option value="JavaScript" <?php if($filter_skill=="JavaScript") echo "selected"; ?>>🟨 JavaScript</option>
            <option value="Junior" <?php if($filter_skill=="Junior") echo "selected"; ?>>👶 Junior</option>
            <option value="Marketing" <?php if($filter_skill=="Marketing") echo "selected"; ?>>🎯 Marketing</option>
            <option value="MySQL" <?php if($filter_skill=="MySQL") echo "selected"; ?>>🗄️ MySQL</option>
            <option value="PHP" <?php if($filter_skill=="PHP") echo "selected"; ?>>🐘 PHP</option>
            <option value="Python" <?php if($filter_skill=="Python") echo "selected"; ?>>🐍 Python</option>
            <option value="Ruby" <?php if($filter_skill=="Ruby") echo "selected"; ?>>💎 Ruby</option>
            <option value="Senior" <?php if($filter_skill=="Senior") echo "selected"; ?>>🔝 Senior</option>
            <option value="SysAdmin" <?php if($filter_skill=="SysAdmin") echo "selected"; ?>>💻 SysAdmin</option>
            <option value="VideoEditor" <?php if($filter_skill=="VideoEditor") echo "selected"; ?>>📷 VideoEditor</option>
            <option value="Other" <?php if($filter_skill=="Other") echo "selected"; ?>>❓ Other</option>
          </select>
        </div>
        <!-- Country filter -->
        <div class="col-md-4">
          <label for="filterCountry" class="form-label">Location🌍</label>
          <select class="form-control" id="filterCountry" name="country">
            <option value="">All Locations</option>
            <optgroup label="Worldwide">
              <option value="Worldwide">Worldwide</option>
            </optgroup>
            <optgroup label="Continents">
                <option value="AF">Africa</option>
                <option value="AS">Asia</option>
                <option value="EU">Europe</option>
                <option value="NA">North America</option>
                <option value="OC">Oceania</option>
                <option value="SA">South America</option>
            </optgroup>
            <optgroup label="Countries" id="filter-country-options">
            </optgroup>
          </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary">Filter</button>
          <button type="button" id="clearFilters" class="btn btn-secondary">Clear</button>
        </div>
      </div>
    </form>
  </div>
  <div class="general-container">
    <!-- Contenedor de las dos columnas -->
    <div class="BoxesSpace two-columns">
      <!-- Columna de Startups Populares -->
      <div class="column popular-column">
      <h2>Popular Startups <img src="../../img/gifs/fire2.gif" alt="fire" class="fire-icon"></h2>
        <?php if (!empty($popularStartups)): ?>
          <?php foreach ($popularStartups as $st): ?>
            <!-- Al hacer clic en la caja se alterna la clase 'expanded' -->
            <div class="Box popular" onclick="this.classList.toggle('expanded');">
              <!-- Información siempre visible -->
             <p class="STname">
                <?php echo htmlspecialchars($st['st_name']); ?> - 📍
                <?php if ($st['place'] === "Worldwide"): ?>
                  Worldwide 🌍
                <?php else: ?>
                    <?php echo htmlspecialchars($st['place']); ?>
                    <img src="<?php echo getFlagImageUrl($st['place']); ?>" alt="<?php echo htmlspecialchars($st['place']); ?>" width="25">
                <?php endif; ?>
            </p>
              <div class="visible-details">
                <p class="bt"><strong>Joining incentives:</strong>💰<?php echo htmlspecialchars($st['rewards']); ?></p>
                <p class="bt"><strong>Sector:</strong> <?php echo getSectorEmoji($st['sector']) . " " . htmlspecialchars($st['sector']); ?></p>
              </div>
              <!-- Información adicional oculta en estado colapsado -->
              <div class="extra-details">
                <p class="bt"><strong>Description: </strong><?php echo htmlspecialchars($st['descr']) ?></p>
                <p class="bt"><strong>Abilities to Join:</strong> <?php echo getSkillEmoji($st['skills']) . " " . htmlspecialchars($st['skills']); ?></p>
                <p class="bt"><strong>Job openings:</strong> <?php echo htmlspecialchars($st['vacantes'])?> </p>
                <button class="Apply" 
                  data-startup-id="<?php echo $st['idst']; ?>" 
                  onclick="event.stopPropagation();">Apply</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-danger">No popular startups available.</div>
        <?php endif; ?>
      </div>

      <!-- Columna de Startups Nuevas -->
      <div class="column new-column">
        <h3>New Startups <img src="../../img/gifs/Clock.gif" alt="Clock" class="clock-icon"> </h2>
        <?php if (!empty($newStartups)): ?>
          <?php foreach ($newStartups as $st): ?>
            <div class="Box new" onclick="this.classList.toggle('expanded');">
              <p class="STname">
                <?php echo htmlspecialchars($st['st_name']); ?> -📍
                <?php if ($st['place'] === "Worldwide"): ?>
                  Worldwide 🌍
                <?php else: ?>
                    <?php echo htmlspecialchars($st['place']); ?>
                    <img src="<?php echo getFlagImageUrl($st['place']); ?>" alt="<?php echo htmlspecialchars($st['place']); ?>" width="25">
                <?php endif; ?>
            </p>
              <div class="visible-details">
                <p class="bt"><strong>Joining Reward:</strong>💰<?php echo htmlspecialchars($st['rewards']); ?></p>
                <p class="bt"><strong>Sector:</strong> <?php echo getSectorEmoji($st['sector']) . " " . htmlspecialchars($st['sector']); ?></p>
              </div>
              <div class="extra-details">
                <p class="bt"><strong>Description: </strong><?php echo htmlspecialchars($st['descr']) ?></p>
                <p class="bt"><strong>Abilities to Join:</strong> <?php echo getSkillEmoji($st['skills']) . " " . htmlspecialchars($st['skills']); ?></p>
                <p class="bt"><strong>Job openings:</strong> <?php echo htmlspecialchars($st['vacantes'])?> </p>
                <button class="Apply" data-startup-id="<?php echo $st['idst']; ?>" onclick="event.stopPropagation();">Apply</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-danger">No new startups available.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <footer>
    <div class="lfooter"></div>
    <div class="Social-Net">  
      <a href="mailto:gzpardo04@gmail.com"><i class="social fas fa-envelope"></i></a>
      <a href="#"><i class="social fa-brands fa-twitter"></i></a>
      <a href="#"><i class="social fa-brands fa-instagram"></i></a>
      <a href="#"><i class="social fa-brands fa-tiktok"></i></a>
    </div>
  </footer>
  <!-- Modal of startup apply confirmation-->
  <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="applyModalLabel"><strong>Confirm Application</strong></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to <strong>apply</strong> to this startup? <br> This will <strong>cost one of your requests.</strong> <br>You will be redirected to the <strong>startup owner's email.</strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="CancelBtn" data-bs-dismiss="modal"><strong>Cancel</strong></button>
          <button type="button" class="btn btn-primary" id="confirmApplyBtn"><strong>Apply</strong></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Error Msg -->
  <div class="toast-container position-fixed   p-3" style="z-index: 1050;">
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <!-- Aquí se mostrará el mensaje de error -->
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

    <!-- Modal de Confirmación para Aplicar -->
  <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="applyModalLabel"><strong>Confirm Application</strong></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to <strong>apply</strong> to this startup?<br>
          This will <strong>cost one of your requests</strong> and redirect you to the <strong>startup owner's email</strong>.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><strong>Cancel</strong></button>
          <button type="button" class="btn btn-primary" id="confirmApplyBtn"><strong>Apply</strong></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Confirmation apply msg-->
  <div class="toast-container position-fixed p-3" style="z-index: 1050;">
    <div id="successToast" class="toast align-items-center text-white  border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Application successful.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('profileButton').addEventListener('click', function(){
      window.location.href = 'Profile.php';
    });
    document.getElementById('PostBtn').addEventListener('click', function(){
      window.location.href = 'Post.php';
    });
    document.getElementById('AboutUsButton').addEventListener('click', function(){
      window.location.href = 'AboutUs.php';
    });
    document.getElementById('clearFilters').addEventListener('click', function(){
      //Reset the form using query selector(select de object using css selector)
      document.querySelector('.filter-form').reset();
      //Redirect to the page without filters
      window.location.href = 'Inicio.php';
    });
        fetch('https://restcountries.com/v2/all')
      .then(response => response.json())
      .then(data => {
        const countryOptions = document.getElementById('filter-country-options');
        data.forEach(country => {
          const option = document.createElement('option');
          option.value = country.alpha2Code;
          option.textContent = `${country.alpha2Code} - ${country.name}`;
          countryOptions.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching country data:', error));
      //Startup apply logic
      
      //Store id of the selected startup
    var currentStartupId = null;
    
    // Asocia un event listener a cada botón "Apply"
    document.querySelectorAll('.Apply').forEach(function(btn) {
      btn.addEventListener('click', function(event) {
        event.stopPropagation(); //Prevent the activation of the click event of the parent element
        // Take the startup id that have been clicked
        currentStartupId = this.getAttribute('data-startup-id');
        //Show the confirmation modal
        var applyModal = new bootstrap.Modal(document.getElementById('applyModal'));
        applyModal.show();
      });
    });
    
    //When the user confirms the apply
    document.getElementById('confirmApplyBtn').addEventListener('click', function() {
      var formData = new FormData();
      formData.append('startup_id', currentStartupId);
      
      fetch('../Back-end/ApplyStartup.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
          var applyModalEl = document.getElementById('applyModal');
          var modalInstance = bootstrap.Modal.getInstance(applyModalEl);
          if(modalInstance) {
            modalInstance.hide();
          }
        if (data.success) {
          var successToastEl = document.getElementById('successToast');
          var successToast = new bootstrap.Toast(successToastEl, { delay: 10000, autohide: true });
          successToast.show();
          // Redirect the user to the publisher email
          window.location.href = 'mailto:' + data.publisher_email;;
        } else {
          var errorToastEl = document.getElementById('errorToast', { delay: 10000, autohide: true });
          var toastBody = errorToastEl.querySelector('.toast-body');
          toastBody.textContent = data.error;
          var errorToast = new bootstrap.Toast(errorToastEl);
          errorToast.show();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        var applyModalEl = document.getElementById('applyModal');
        var applyModalInstance = bootstrap.Modal.getInstance(applyModalEl);
        if(applyModalInstance !== null) {
            applyModalInstance.hide();
        }
        var errorToastEl = document.getElementById('errorToast');
        var toastBody = errorToastEl.querySelector('.toast-body');
        toastBody.textContent = "An unexpected error occurred.";
        var errorToast = new bootstrap.Toast(errorToastEl, { delay: 10000, autohide: true });
        errorToast.show();
      });
    });


  </script>
</body>
</html>
