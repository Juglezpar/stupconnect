<?php
// Función para obtener el emoji según el sector
function getSectorEmoji($sector) {
    $sectors = [
        "ClimateTech" => "🌍",
        "Cybersecurity" => "🛡️",
        "E-commerce" => "🛒",
        "Education" => "🎓",
        "Finance" => "💰",
        "Health" => "🏥",
        "IA" => "🤖",
        "Marketing" => "📣",
        "Mobile Apps" => "📱",
        "Other" => "❓",
        "Retail" => "🛍️",
        "Technology" => "💻",
        "Web3" => "🌐",
        "Web development" => "🛠️",
        "Youtube" => "📹"
    ];
    
    return $sectors[$sector] ?? "❔"; // Devuelve "❔" si el sector no está en la lista
}

// Función para obtener el emoji según la habilidad (skill)
function getSkillEmoji($skill) {
    $skills = [
        "Android" => "🤖",
        "Backend" => "🖥️",
        "C++" => "💻",
        "Cloud" => "☁️",
        "Crypto" => "💎",
        "Design" => "🎨",
        "DevOps" => "🛠️",
        "Engineer" => "🔧",
        "Finance" => "💰",
        "FrontEnd" => "🌐",
        "FullStack" => "💼",
        "Golang" => "🐹",
        "Java" => "☕",
        "JavaScript" => "🟨",
        "Junior" => "👶",
        "Marketing" => "🎯",
        "MySQL" => "🗄️",
        "PHP" => "🐘",
        "Python" => "🐍",
        "Ruby" => "💎",
        "Senior" => "🔝",
        "SysAdmin" => "💻",
        "VideoEditor" => "📷",
        "Other" => "❓"
    ];
    
    return $skills[$skill] ?? "❔"; // Devuelve "❔" si la habilidad no está en la lista
}


// Function to get the flag emoji from the country code

function getFlagImageUrl($alpha2) {
  if ($alpha2 === "Worldwide") {
        return "🌍"; // Imagen de un globo terráqueo
    }
    return "https://flagcdn.com/w40/" . strtolower($alpha2) . ".png"; // Convierte a minúsculas para que funcione con FlagCDN
}
?>