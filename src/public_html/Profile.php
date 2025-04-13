<?php
require_once '../Back-end/DAO.php';
require_once '../Back-end/utils.php'; 
session_start();

// Instanciamos DAO
$dao = new DAO();

// Take the Session data of the user
$user = json_decode($_SESSION['UserG']);

//Using the email of the user, we search in the database for the user
$sql_user = "SELECT * FROM users WHERE email = :email";
$user_result = $dao->ExecuteQuery($sql_user, ["email" => $user->email]);

if (empty($user_result)) {
    //if the user is not found, we redirect to the index page
    header("Location: index.php");
    exit;
}

//Take the id and the remainng post that the user have
$user_id   = $user_result[0]['id'];
$remaining = $user_result[0]['remaining'];
$applications= $user_result[0]['solicitudes_disponibles'];

//Search for the startups that the user has published
//Query that joins the own table with the startup table to get the startups that the user has published
$sql_startups = "
    SELECT s.idst, s.st_name, s.sector, s.rewards, s.descr, 
           s.place, s.skills, s.vacantes, s.created
      FROM own o
      JOIN startup s ON s.idst = o.IdStart
     WHERE o.idUser = :uid
";
$myStartups = $dao->ExecuteQuery($sql_startups, ["uid" => $user_id]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyProfile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Iconos / Otros estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="Footer.css" rel="stylesheet">
    <link href="Post.css" rel="stylesheet">
    <link href="Profile.css" rel="stylesheet">
    <link href="Footer.css" rel="stylesheet">
    <link href="responsive3.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <!-- Googlefonts -->
    <link href="https://fonts.googleapis.com/css2?family=Goblin+One&display=swap" rel="stylesheet">

</head>
<body>
    <div class="header">
        <div class="containerHeader">
            <button class="WEBName" id="LogoButton">StUpConnect</button>
            <h2>MY Profile</h2>
            <button class="customer-support-btn">
                💬 Contact us
            </button>
        </div>
    </div>
    
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user->Picture); ?>" 
                 alt="Foto de perfil" class="profile-picture"
                 onerror="this.onerror=null;this.src='../img/defpfp.png';">
            <h1 class="profile-name"><?php echo htmlspecialchars($user->name); ?></h1>
        </div>

        <div class="profile-details">
            <div class="upperColums"> 
            <div class="detail">
                    <h3>Startup Contact Email</h3>
                    <p><?php echo htmlspecialchars($user->email); ?></p>
                </div>  
                <div class="detail">
                    <h3>Profile creation date:</h3>
                    <p>9/11/2024</p>
                </div>
                <div class="detail">
                    <h3>Remaining posts:</h3>
                    <p><?php echo htmlspecialchars($remaining); ?></p>
                </div>  
                <div class="detail">
                    <h3>Remaining applications:</h3>
                    <p><?php echo htmlspecialchars($applications); ?></p>
                </div>
            </div>

        <div class="StContainer">
    <h3>Published startups</h3>

    <?php if (!empty($myStartups)): ?>
        <!-- Creamos una fila general -->
        <div class="StartUp-Row">
            <?php foreach($myStartups as $st): ?>
                <?php $collapseId = "collapse-" . $st['idst']; ?>
                <button id="St_button" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>">
                    <span class="button-title">
                        <?php echo htmlspecialchars($st['st_name']); ?>
                    </span>
                    <span class="button-date">
                        <?php echo date("d/m/Y", strtotime($st['created'])); ?>
                    </span>

                </button>
                <div>
                    <div class="collapse collapse-horizontal" id="<?php echo $collapseId; ?>">
                        <div class="card card-body" id="CardInfo"style="width: 37rem;">
                            <span class="stName">
                                <b><?php echo htmlspecialchars($st['st_name']); ?></b>
                            </span>
                            <span class="stDescr">
                               <b>Description: </b><?php echo htmlspecialchars($st['descr']); ?>
                            </span>
                            <span class="stDescr">
                                <b>Sector: </b><?php echo getSectorEmoji($st['sector']) . " " . htmlspecialchars($st['sector']); ?>
                            </span>
                            <span class="stDescr">
                                <b>Rewards: </b>💰<?php echo htmlspecialchars($st['rewards']); ?>
                            </span>
                            <span class="stDescr">
                                <b>Place(Initials): </b><?php if ($st['place'] === "Worldwide"): ?>
                                                        Worldwide 🌍
                                                        <?php else: ?>
                                                            <?php echo htmlspecialchars($st['place']); ?>
                                                            <img src="<?php echo getFlagImageUrl($st['place']); ?>" alt="<?php echo htmlspecialchars($st['place']); ?>" width="25">
                                                        <?php endif; ?>
                            </span>
                            <span class="stDescr">
                                <b>Skills recomended to apply : </b><?php echo getSkillEmoji($st['skills']) . " " . htmlspecialchars($st['skills']); ?>
                            </span>
                            <span class="stDescr">
                                <b>Spots left: </b><?php echo htmlspecialchars($st['vacantes']); ?>
                            </span>
                            <div class="btn-group mt-3" role="group">
                            <button type="button" class="btn btn-dark btn-sm" onclick="editarStartup(<?php echo htmlspecialchars($st['idst']); ?>)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarStartup(<?php echo htmlspecialchars($st['idst']); ?>, '<?php echo htmlspecialchars($st['st_name']); ?>')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div> 
    <?php else: ?>
        <div class="alert alert-danger mt-3">
            You haven't published any startups yet.
        </div>
    <?php endif; ?>
</div>
            </div><!-- ./StContainer -->

        </div><!-- ./profile-details -->
    </div><!-- ./profile-container -->

    <footer>
        <div class="lfooter"></div>
        <div class="Social-Net">  
            <a href="mailto:gzpardo04@gmail.com"><i class=" social fas fa-envelope"></i></a>
            <a href="#"><i class=" social fa-brands fa-twitter"></i></a>
            <a href="#"><i class=" social fa-brands fa-instagram"></i></a>
            <a href="#"><i class=" social fa-brands fa-tiktok"></i></a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!------------------------------------------------MODAL------------------------------------------------------->
<!-- Modal for Editing Startup -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Editar Startup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para editar la startup -->
        <form id="editStartupForm">
          <input type="hidden" id="editStartupId" name="id">
          <div class="mb-3">
            <label for="startupDescription" class="form-label">Description</label>
            <textarea class="form-control" id="startupDescription" name="descr"></textarea>
          </div>
          <div class="mb-3">
            <label for="startupSector" class="form-label">Sector</label>
           <label class="form-label">Startup sector</label>
                <select class="form-control" id="startupSector" name="sector" required>
                    <option value="">-- Select Sector --</option>
                    <option value="ClimateTech">🌍 ClimateTech</option>
                    <option value="Cybersecurity">🛡️ Cybersecurity</option>
                    <option value="E-commerce">🛒 E-commerce</option>
                    <option value="Education">🎓 Education</option>
                    <option value="Finance">💰 Finance</option>
                    <option value="Health">🏥 Health</option>
                    <option value="IA">🤖 IA</option>
                    <option value="Marketing">📣 Marketing</option>
                    <option value="Mobile Apps">📱 Mobile Apps</option>
                    <option value="Other">❓ Other</option>
                    <option value="Retail">🛍️ Retail</option>
                    <option value="Technology">💻 Technology</option>
                    <option value="Web3">🌐 Web3</option>
                    <option value="Web development">🛠️ Web development</option>
                    <option value="Youtube">📹 Youtube</option>
                </select>
          </div>
          <div class="mb-3">
            <label for="startupRewards" class="form-label">Joining incentivess</label>
            <textarea type="text" class="form-control" id="startupRewards" maxlength="255" name="rewards"></textarea>
          </div>
          <div class="mb-3">
                <label class="form-label">Required Skills</label>
                <select class="form-control" id="skills" name="skills" required>
                    <option value="">-- Select Skill --</option>
                    <option value="Android">🤖 Android</option>
                    <option value="Backend">🖥️ Backend</option>
                    <option value="C++">💻 C++</option>
                    <option value="Cloud">☁️ Cloud</option>
                    <option value="Crypto">💎 Crypto</option>
                    <option value="Design">🎨 Design</option>
                    <option value="DevOps">🛠️ DevOps</option>
                    <option value="Engineer">🔧 Engineer</option>
                    <option value="Finance">💰 Finance</option>
                    <option value="FrontEnd">🌐 FrontEnd</option>
                    <option value="FullStack">💼 FullStack</option>
                    <option value="Golang">🐹 Golang</option>
                    <option value="Java">☕ Java</option>
                    <option value="JavaScript">🟨 JavaScript</option>
                    <option value="Junior">👶 Junior</option>
                    <option value="Marketing">🎯 Marketing</option>
                    <option value="MySQL">🗄️ MySQL</option>
                    <option value="PHP">🐘 PHP</option>
                    <option value="Python">🐍 Python</option>
                    <option value="Ruby">💎 Ruby</option>
                    <option value="Senior">🔝 Senior</option>
                    <option value="SysAdmin">💻 SysAdmin</option>
                    <option value="VideoEditor">📷 VideoEditor</option>
                    <option value="Other">❓ Other</option>
                </select>
            </div>
          <div class="mb-3">
          <label for="location" class="form-label">Any Location restrictions</label>
                <select name="location" id="location" required>
                    <option value="">-- Select a Location --</option>
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
                    <optgroup label="Countries" id="country-options">
                        <!-- Country options will be populated here -->
                    </optgroup>
                </select>
          </div>
          <div class="mb-3">
            <label  class="form-label">Remaining spots </label>
            <input type="number" id="Spots" name="Spots" min="1" max="20" placeholder="Enter remaining spots" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Confirmar Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Startup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="DeleteModalBody">
        <!-- Confimation message taken  for de JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Confirmation apply msg-->
  <div class="toast-container position-fixed p-3" style="z-index: 1050;">
    <div id="successToast" class="toast align-items-center text-white  border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Edit successful
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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

<!------------------------------------------------SCRIPTS------------------------------------------------------->
<script>
        //Redirect to the main page when the Logo button is selected 
        document.getElementById('LogoButton').addEventListener('click', function(){
            window.location.href = 'Inicio.php';
        });
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        var currentStartupId; // Almacenará el ID de la startup seleccionada

        function showSuccessToast(message) {
        let toastEl = document.getElementById('successToast');
        toastEl.querySelector('.toast-body').textContent = message;
        let toast = new bootstrap.Toast(toastEl, { delay: 3000, autohide: true });
        toast.show();
        }

        function showErrorToast(message) {
        let toastEl = document.getElementById('errorToast');
        toastEl.querySelector('.toast-body').textContent = message;
        let toast = new bootstrap.Toast(toastEl, { delay: 3000, autohide: true });
        toast.show();
        }


        function editarStartup(id) {
            //Show the actual content of the startup
            currentStartupId = id;
            fetch('../Back-end/getStartup.php?id=' + id)
            .then(response => {
            if (!response.ok) {
                showErrorToast('An error occurred while loading the data. Please try again.');
            }
            return response.json();
        })
        .then(data => {
            if(data.error) {
                alert('Error: ' + data.error);
                return;
            }
            //Fill with the actual startup data
            document.querySelector('#editStartupForm input[name="id"]').value = data.idst;
            document.getElementById('startupDescription').value = data.descr;
            document.getElementById('startupSector').value = data.sector;
            document.getElementById('startupRewards').value = data.rewards;
            document.getElementById('skills').value = data.skills;
            document.getElementById('location').value = data.place;
            document.getElementById('Spots').value = data.vacantes
        })
        .catch(error => {
            console.error('Error fetching startup data:', error);
            alert('Ocurrió un error al cargar los datos. Inténtalo de nuevo.');
        });
            editModal.show();
        }

        function eliminarStartup(id, name) {
            currentStartupId = id;
            document.getElementById('DeleteModalBody').innerHTML = "Are you sure you want to delete <strong>" + name + "</strong>?";
            deleteModal.show();
        }

        document.getElementById('saveEditBtn').addEventListener('click', function() {
            // Recopila los datos del formulario
            var formData = new FormData(document.getElementById('editStartupForm'));
            
            // Envía los datos mediante AJAX al script que actualiza la startup
            fetch('../Back-end/EditStartup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editModal.hide();
                    showSuccessToast('Successful edit');
                    // wait 2 seconds before reload the page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorToast('Error saving the changes: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                 showErrorToast('An unexpected error occurred. Please try again.');
            });
        });


        // Evento para confirmar la eliminación en el modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Creamos un objeto FormData para enviar el ID
            let formData = new FormData();
            formData.append('id', currentStartupId);

            fetch('../Back-end/DeleteStartup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    deleteModal.hide();
                    showSuccessToast('Successful Delete');
                    // wait 2 seconds before reload the page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    deleteModal.hide();
                    showErrorToast('Error saving the delete: ' + data.error);
                }
            })
            .catch(error => {
                showErrorToast('An unexpected error occurred. Please try again.');
            });
        });

    // Fetch country data and populate the country options 
     // Fetch country data and populate the country options 
     fetch('https://restcountries.com/v2/all')
            .then(response => response.json()) //Convert the response to JavaScript object
            .then(data => {
                const countryOptions = document.getElementById('country-options');
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.alpha2Code;
                    option.textContent = `${country.alpha2Code} - ${country.name}`;
                    countryOptions.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching country data:', error));
</script>
</body>
</html>



