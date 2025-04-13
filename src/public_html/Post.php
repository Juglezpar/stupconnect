<?php

use Google\Service\CloudControlsPartnerService\Console;

ob_start(); // Start the output buffer to prevent the headers from being sent
session_start(); // Start the session to access session variables (Users gmail to link in the new startup)
require __DIR__ . '/../Back-end/DAO.php';
//Variable of confirmation/error message
$message="";
$messageType="";
//Create a new DAO instance
$dao = new DAO();
// Verifica que el usuario haya iniciado sesión
$user = json_decode($_SESSION['UserG']);
if (!isset($user->email)) {
    header("Location: index.php"); // Redirect to de index page if the Session dont have the email set (Usuario sim)
    exit;
}
$user_email = $user->email; // Get the email from the session
//Take de user in the database
$sql_user = "SELECT * FROM users WHERE email = :email";
$user_result = $dao->ExecuteQuery($sql_user, ["email" => $user_email]);

if(!empty($user_result)){ 
    $user_id = $user_result[0]['id']; // Get the user id from the database
    $remaining = $user_result[0]['remaining']; // Remaining startups the user can post

    if($remaining >0){ //The user still havee remainig startups to post

        if($_SERVER['REQUEST_METHOD'] === 'POST'){ //Server ask for a post request, when we sumbit the form
            $st_name = isset($_POST['startupName']) ? $_POST['startupName'] : '';
            $sector = isset($_POST['sector']) ? $_POST['sector'] : '';
            $rewards = isset($_POST['incentives']) ? $_POST['incentives'] : '';
            $descr = isset($_POST['description']) ? $_POST['description'] : '';
            $place = isset($_POST['location']) ? $_POST['location'] : '';
            $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
            $vacantes = isset($_POST['Spots']) ? $_POST['Spots'] : '';
            error_log("st_name: $st_name, sector: $sector, rewards: $rewards, descr: $descr, place: $place, skills: $skills, vacantes: $vacantes");

            //Insert the data into the database
            //Prepare the query
            $sql= "INSERT INTO startup (st_name, sector, rewards, descr, place, skills, vacantes)"
                    . "VALUES (:st_name, :sector, :rewards, :descr, :place, :skills, :vacantes)";
                    //FALTA GESTIONAR EL ERROR (DUPLICIDAD DE DATOS)
            $param = array( // Put the data into an array to pass it to the DAO
                "st_name" => $st_name,
                "sector" => $sector,
                "rewards" => $rewards,
                "descr" => $descr,
                "place" => $place,
                "skills" => $skills,
                "vacantes" => $vacantes
            );
            //Execute the query
            $result = $dao->InsertData($sql, $param);
            
            if($result <0){ // If no rows were affected, consider it an error
                $message = "Failed to insert the startup, try later.";
                $messageType = "danger";

            }else{
                $sql_lastId = "SELECT LAST_INSERT_ID() AS last_id";
                $result_lastId = $dao->ExecuteQuery($sql_lastId);
                if (!empty($result_lastId)) {
                    $startup_id = $result_lastId[0]['last_id']; 
                }
                //Create the relation between the user and the startup
                $sql_own= "INSERT INTO own (idUser, IdStart)"
                    . "VALUES (:idUser, :IdStartup)";
                $param_own = [
                    "idUser" => $user_id,
                    "IdStartup" => $startup_id
                ];
                $dao ->InsertData($sql_own, $param_own); // Insert the relation into the database
                //Update the remaining startups the user can post
                $sql_update = "UPDATE users SET remaining = remaining - 1 WHERE id = :id";
                $param_update= ["id" => $user_id];
                $dao->InsertData($sql_update, $param_update); // Update the remaining startups the user can post
                $message = "¡Startup publicada con éxito!";
                $messageType = "success";
            }
        }
    }
    else { 
        $message = "You have reached the limit of startups you can publish.";
        $messageType = "danger";
    }
}
else {
    $message = "User not found.";
    $messageType = "danger";
}
ob_end_flush(); // Send the output buffer and turn off output buffering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="Post.css" rel="stylesheet">
    <link href="Footer.css" rel="stylesheet">
    <link href="responsive4.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">

    <!-- Googlefonts -->
    <link href="https://fonts.googleapis.com/css2?family=Goblin+One&display=swap" rel="stylesheet">
    <title>Post</title>
</head>
<body>
    <div class="header">
        <div class="containerHeader">
        <button class="WEBName" id="LogoButton">StUpConnect</button>
            <h2> Post your StartUp</h2>
            <button class="customer-support-btn">
                💬 Contact us
            </button>
        </div>
    </div>
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>" role="alert">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

        
    <div class="Form">
        <form class="container  p-4 rounded" action="Post.php" method="post">
            <div class="mb-3">
                <label  class="form-label">💡 Startup Name</label>
                <input type="text" class="form-control" id="startupName" name="startupName" maxlength="100" placeholder="Enter your startup name" required>
            </div>
            
            <div class="mb-3">
                <label  class="form-label">📝 Description</label>
                <textarea class="form-control" id="description" name="description"  maxlength="4000"rows="4" placeholder="Describe your startup" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">💼 Startup sector</label>
                <select class="form-control" id="sector" name="sector" required>
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
                <label  class="form-label">💰 Joining incentives</label>
                <input type="text" class="form-control" id="incentives" name="incentives" maxlength="255" placeholder="Enter reward details (Ex: %Of the StartUp | Salary | Other rewards)" required>
            </div>           
            <div class="mb-3">
                <label class="form-label">🎯 Required Skills</label>
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
                <label for="location" class="form-label">🌎 Location</label>
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
                <label  class="form-label">📌 Remaining spots </label>
                <input type="number" id="Spots" name="Spots" min="1" max="20" placeholder="Enter remaining spots" required>
            </div>
            <button type="submit" class="btn btn-primary">Post StartUp</button>
        </form>
    </div>
    <footer>
        <div class="lfooter">
        </div>
         <!-- Follow on X and Instagram -->
         <div class="Social-Net">  
            <a href="mailto:gzpardo04@gmail.com"><i class=" social fas fa-envelope"></i></a>
            <a href=#><i class=" social fa-brands fa-twitter"></i></a>
            <a href=#><i class=" social fa-brands fa-instagram"></i></a>
            <a href=#><i class=" social fa-brands fa-tiktok"></i></a>
        </div>
    </footer>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="./js/index.js" type="module"></script>  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>
    //Redirect to the main page when the Logo button is selected 
    document.getElementById('LogoButton').addEventListener('click', function(){
        //Redirect
        window.location.href = 'Inicio.php';
    })
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
